<?php

namespace App\Command;

use App\Entity\Pronostic;
use App\Entity\Rencontre;
use App\Repository\EquipeRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed-demo-data',
    description: 'Crée une rencontre et un pronostic de démonstration à partir des équipes déjà synchronisées',
)]
class SeedDemoDataCommand extends Command
{
    public function __construct(
        private readonly EquipeRepository $equipeRepository,
        private readonly UtilisateurRepository $utilisateurRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $france = $this->equipeRepository->findOneBy(['codeFifa' => 'FRA']);
        $allemagne = $this->equipeRepository->findOneBy(['codeFifa' => 'GER']);
        $utilisateur = $this->utilisateurRepository->findOneBy(['email' => 'test@wc26.fr']);

        if (!$france || !$allemagne) {
            $io->error('Équipes FRA et/ou GER introuvables. Lance d\'abord php bin/console app:sync-equipes.');

            return Command::FAILURE;
        }

        if (!$utilisateur) {
            $io->error('Utilisateur de test introuvable. Lance d\'abord php bin/console doctrine:fixtures:load.');

            return Command::FAILURE;
        }

        // Rencontre de démonstration avec un résultat déjà connu
        $rencontre = new Rencontre();
        $rencontre->setEquipeDomicile($france);
        $rencontre->setEquipeExterieur($allemagne);
        $rencontre->setDateHeure(new \DateTime('2026-06-12 20:00:00'));
        $rencontre->setScoreDomicile(2);
        $rencontre->setScoreExterieur(1);
        $rencontre->setStatut('terminé');
        $rencontre->setPhase('poules');
        $this->entityManager->persist($rencontre);

        // Pronostic de test : score exact deviné
        $pronostic = new Pronostic();
        $pronostic->setUtilisateur($utilisateur);
        $pronostic->setRencontre($rencontre);
        $pronostic->setScoreDomicilePronostique(2);
        $pronostic->setScoreExterieurPronostique(1);
        $this->entityManager->persist($pronostic);

        $this->entityManager->flush();

        $io->success('Rencontre France-Allemagne (2-1) et pronostic de test créés.');

        return Command::SUCCESS;
    }
}