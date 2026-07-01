<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('test@wc26.fr');
        $utilisateur->setPseudo('Ilyes');
        $utilisateur->setMotDePasse('motdepasse_a_hasher_plus_tard');
        $utilisateur->setRoles(['ROLE_USER']);
        $manager->persist($utilisateur);

        $manager->flush();
    }
}