<?php

namespace App\Command;

use App\Service\RencontreSyncService;
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
        private readonly RencontreSyncService $rencontreSyncService,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info("Appel de l'API football-data.org...");

        try {
            $resultat = $this->rencontreSyncService->synchroniser();
        } catch (\Exception $e) {
            $io->error('Erreur lors de l\'appel API : ' . $e->getMessage());

            return Command::FAILURE;
        }

        $io->success("{$resultat['creees']} rencontre(s) créée(s), {$resultat['misesAJour']} mise(s) à jour, {$resultat['ignorees']} ignorée(s).");

        return Command::SUCCESS;
    }
}