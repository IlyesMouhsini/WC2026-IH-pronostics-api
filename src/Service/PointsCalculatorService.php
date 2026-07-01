<?php

namespace App\Service;

use App\Entity\Pronostic;

class PointsCalculatorService
{
    private const POINTS_SCORE_EXACT = 3;
    private const POINTS_BON_RESULTAT = 1;
    private const POINTS_MAUVAIS_RESULTAT = 0;

    public function calculerPoints(Pronostic $pronostic): int
    {
        $rencontre = $pronostic->getRencontre();

        $scoreDomicileReel = $rencontre->getScoreDomicile();
        $scoreExterieurReel = $rencontre->getScoreExterieur();

        // Le match n'a pas encore de résultat, impossible de calculer
        if ($scoreDomicileReel === null || $scoreExterieurReel === null) {
            throw new \LogicException('Impossible de calculer les points : la rencontre n\'a pas encore de résultat.');
        }

        $scoreDomicilePronostique = $pronostic->getScoreDomicilePronostique();
        $scoreExterieurPronostique = $pronostic->getScoreExterieurPronostique();

        // Cas 1 : score exact
        if ($scoreDomicilePronostique === $scoreDomicileReel && $scoreExterieurPronostique === $scoreExterieurReel) {
            return self::POINTS_SCORE_EXACT;
        }

        // Cas 2 : bon résultat (même issue : victoire domicile / nul / victoire extérieur)
        if ($this->determinerIssue($scoreDomicilePronostique, $scoreExterieurPronostique)
            === $this->determinerIssue($scoreDomicileReel, $scoreExterieurReel)) {
            return self::POINTS_BON_RESULTAT;
        }

        // Cas 3 : mauvais résultat
        return self::POINTS_MAUVAIS_RESULTAT;
    }

    /**
     * Détermine l'issue d'un match : 1 (victoire domicile), 0 (nul), -1 (victoire extérieur)
     */
    private function determinerIssue(int $scoreDomicile, int $scoreExterieur): int
    {
        return $scoreDomicile <=> $scoreExterieur;
    }
}