<?php

namespace App\Service;

use App\Entity\Rencontre;
use App\Repository\EquipeRepository;
use App\Repository\RencontreRepository;
use Doctrine\ORM\EntityManagerInterface;

class RencontreSyncService
{
    public function __construct(
        private readonly FootballApiService $footballApiService,
        private readonly EquipeRepository $equipeRepository,
        private readonly RencontreRepository $rencontreRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function synchroniser(string $codeCompetition = 'WC'): array
    {
        $matchsApi = $this->footballApiService->getRencontresCompetition($codeCompetition);

        $nbCreees = 0;
        $nbMisesAJour = 0;
        $nbIgnorees = 0;

        foreach ($matchsApi as $matchApi) {
            $codeDomicile = $matchApi['homeTeam']['tla'] ?? null;
            $codeExterieur = $matchApi['awayTeam']['tla'] ?? null;

            if (!$codeDomicile || !$codeExterieur) {
                $nbIgnorees++;
                continue;
            }

            $equipeDomicile = $this->equipeRepository->findOneBy(['codeFifa' => $codeDomicile]);
            $equipeExterieur = $this->equipeRepository->findOneBy(['codeFifa' => $codeExterieur]);

            if (!$equipeDomicile || !$equipeExterieur) {
                $nbIgnorees++;
                continue;
            }

            $dateHeure = new \DateTime($matchApi['utcDate']);

            $rencontre = $this->rencontreRepository->findOneBy([
                'equipeDomicile' => $equipeDomicile,
                'equipeExterieur' => $equipeExterieur,
                'dateHeure' => $dateHeure,
            ]);

            $estNouvelle = false;
            if (!$rencontre) {
                $rencontre = new Rencontre();
                $rencontre->setEquipeDomicile($equipeDomicile);
                $rencontre->setEquipeExterieur($equipeExterieur);
                $rencontre->setDateHeure($dateHeure);
                $estNouvelle = true;
            }

            $rencontre->setStatut($this->traduireStatut($matchApi['status'] ?? 'SCHEDULED'));
            $rencontre->setPhase(strtolower(str_replace('_', ' ', $matchApi['stage'] ?? 'poules')));
            $rencontre->setScoreDomicile($matchApi['score']['fullTime']['home'] ?? null);
            $rencontre->setScoreExterieur($matchApi['score']['fullTime']['away'] ?? null);

            $this->entityManager->persist($rencontre);

            $estNouvelle ? $nbCreees++ : $nbMisesAJour++;
        }

        $this->entityManager->flush();

        return [
            'creees' => $nbCreees,
            'misesAJour' => $nbMisesAJour,
            'ignorees' => $nbIgnorees,
        ];
    }

    private function traduireStatut(string $statutApi): string
    {
        return match ($statutApi) {
            'FINISHED' => 'terminé',
            'IN_PLAY', 'PAUSED', 'LIVE' => 'en cours',
            'POSTPONED', 'SUSPENDED', 'CANCELLED' => 'annulé',
            default => 'à venir',
        };
    }
}