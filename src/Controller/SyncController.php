<?php

namespace App\Controller;

use App\Service\RencontreSyncService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class SyncController
{
    #[Route('/api/sync-rencontres', name: 'sync_rencontres', methods: ['POST'])]
    public function syncRencontres(RencontreSyncService $rencontreSyncService): JsonResponse
    {
        try {
            $resultat = $rencontreSyncService->synchroniser();
        } catch (\Exception $e) {
            return new JsonResponse(['erreur' => $e->getMessage()], 500);
        }

        return new JsonResponse($resultat);
    }
}