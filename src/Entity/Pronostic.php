<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PronosticRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PronosticRepository::class)]
#[ApiResource]
class Pronostic
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'pronostics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'pronostics')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rencontre $rencontre = null;

    #[ORM\Column]
    private ?int $scoreDomicilePronostique = null;

    #[ORM\Column]
    private ?int $scoreExterieurPronostique = null;

    #[ORM\Column(nullable: true)]
    private ?int $points = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getRencontre(): ?Rencontre
    {
        return $this->rencontre;
    }

    public function setRencontre(?Rencontre $rencontre): static
    {
        $this->rencontre = $rencontre;

        return $this;
    }

    public function getScoreDomicilePronostique(): ?int
    {
        return $this->scoreDomicilePronostique;
    }

    public function setScoreDomicilePronostique(int $scoreDomicilePronostique): static
    {
        $this->scoreDomicilePronostique = $scoreDomicilePronostique;

        return $this;
    }

    public function getScoreExterieurPronostique(): ?int
    {
        return $this->scoreExterieurPronostique;
    }

    public function setScoreExterieurPronostique(int $scoreExterieurPronostique): static
    {
        $this->scoreExterieurPronostique = $scoreExterieurPronostique;

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(?int $points): static
    {
        $this->points = $points;

        return $this;
    }
}
