<?php

namespace App\Command;

use App\Entity\Rencontre;
use App\Repository\EquipeRepository;
use App\Repository\RencontreRepository;
use App\Service\FootballApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:sync-rencontres',
    description: 'Récupère les rencontres (matchs, scores inclus) depuis football-data.org',
)]
class SyncRencontresCommand extends Command
{
    public function __construct(
        private readonly FootballApiService $footballApiService,
        private readonly EquipeRepository $equipeRepository,
        private readonly RencontreRepository $rencontreRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $codeCompetition = 'WC';

        $io->info("Appel de l'API football-data.org pour les rencontres de {$codeCompetition}...");

        try {
            $matchsApi = $this->footballApiService->getRencontresCompetition($codeCompetition);
        } catch (\Exception $e) {
            $io->error('Erreur lors de l\'appel API : ' . $e->getMessage());

            return Command::FAILURE;
        }

        $io->info(count($matchsApi) . ' rencontre(s) récupérée(s) depuis l\'API.');

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

            // On cherche si cette rencontre existe déjà, pour la mettre à jour plutôt que la dupliquer
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

        $io->success("{$nbCreees} rencontre(s) créée(s), {$nbMisesAJour} mise(s) à jour, {$nbIgnorees} ignorée(s).");

        return Command::SUCCESS;
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