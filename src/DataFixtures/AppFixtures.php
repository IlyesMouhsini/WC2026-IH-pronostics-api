<?php

namespace App\DataFixtures;

use App\Entity\Equipe;
use App\Entity\Rencontre;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $equipesData = [
            ['nom' => 'France', 'codeFifa' => 'FRA', 'groupe' => 'A'],
            ['nom' => 'Allemagne', 'codeFifa' => 'GER', 'groupe' => 'A'],
            ['nom' => 'Brésil', 'codeFifa' => 'BRA', 'groupe' => 'B'],
            ['nom' => 'Argentine', 'codeFifa' => 'ARG', 'groupe' => 'B'],
            ['nom' => 'États-Unis', 'codeFifa' => 'USA', 'groupe' => 'C'],
            ['nom' => 'Mexique', 'codeFifa' => 'MEX', 'groupe' => 'C'],
            ['nom' => 'Espagne', 'codeFifa' => 'ESP', 'groupe' => 'D'],
            ['nom' => 'Portugal', 'codeFifa' => 'POR', 'groupe' => 'D'],
        ];

        $equipes = [];
        foreach ($equipesData as $data) {
            $equipe = new Equipe();
            $equipe->setNom($data['nom']);
            $equipe->setCodeFifa($data['codeFifa']);
            $equipe->setGroupe($data['groupe']);
            $manager->persist($equipe);

            $equipes[$data['codeFifa']] = $equipe;
        }

        $rencontresData = [
            ['dom' => 'FRA', 'ext' => 'GER', 'date' => '2026-06-12 20:00:00', 'scoreDom' => 2, 'scoreExt' => 1, 'statut' => 'terminé'],
            ['dom' => 'BRA', 'ext' => 'ARG', 'date' => '2026-06-13 17:00:00', 'scoreDom' => null, 'scoreExt' => null, 'statut' => 'à venir'],
            ['dom' => 'USA', 'ext' => 'MEX', 'date' => '2026-06-14 20:00:00', 'scoreDom' => null, 'scoreExt' => null, 'statut' => 'à venir'],
            ['dom' => 'ESP', 'ext' => 'POR', 'date' => '2026-06-15 18:00:00', 'scoreDom' => null, 'scoreExt' => null, 'statut' => 'à venir'],
        ];

        foreach ($rencontresData as $data) {
            $rencontre = new Rencontre();
            $rencontre->setEquipeDomicile($equipes[$data['dom']]);
            $rencontre->setEquipeExterieur($equipes[$data['ext']]);
            $rencontre->setDateHeure(new \DateTime($data['date']));
            $rencontre->setScoreDomicile($data['scoreDom']);
            $rencontre->setScoreExterieur($data['scoreExt']);
            $rencontre->setStatut($data['statut']);
            $rencontre->setPhase('poules');
            $manager->persist($rencontre);
        }

        $manager->flush();
    }
}