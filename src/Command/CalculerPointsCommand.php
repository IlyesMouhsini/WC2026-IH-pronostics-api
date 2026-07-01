<?php

namespace App\Command;

use App\Repository\PronosticRepository;
use App\Service\PointsCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:calculer-points',
    description: 'Calcule les points de tous les pronostics dont la rencontre est terminée',
)]
class CalculerPointsCommand extends Command
{
    public function __construct(
        private readonly PronosticRepository $pronosticRepository,
        private readonly PointsCalculatorService $pointsCalculator,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // On récupère uniquement les pronostics pas encore notés (points = null)
        $pronostics = $this->pronosticRepository->findBy(['points' => null]);

        $io->info(count($pronostics) . ' pronostic(s) en attente de calcul.');

        $nbCalcules = 0;
        $nbIgnores = 0;

        foreach ($pronostics as $pronostic) {
            $rencontre = $pronostic->getRencontre();

            // On ne peut calculer que si le match a bien un résultat
            if ($rencontre->getScoreDomicile() === null || $rencontre->getScoreExterieur() === null) {
                $nbIgnores++;
                continue;
            }

            $points = $this->pointsCalculator->calculerPoints($pronostic);
            $pronostic->setPoints($points);
            $nbCalcules++;
        }

        $this->entityManager->flush();

        $io->success("{$nbCalcules} pronostic(s) noté(s), {$nbIgnores} en attente (match pas encore terminé).");

        return Command::SUCCESS;
    }
}