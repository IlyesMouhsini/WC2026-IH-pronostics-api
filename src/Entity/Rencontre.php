<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RencontreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RencontreRepository::class)]
#[ApiResource]
class Rencontre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateHeure = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreDomicile = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreExterieur = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\Column(length: 20)]
    private ?string $phase = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipe $equipeDomicile = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Equipe $equipeExterieur = null;

    /**
     * @var Collection<int, Pronostic>
     */
    #[ORM\OneToMany(targetEntity: Pronostic::class, mappedBy: 'rencontre')]
    private Collection $pronostics;

    public function __construct()
    {
        $this->pronostics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeure(): ?\DateTime
    {
        return $this->dateHeure;
    }

    public function setDateHeure(\DateTime $dateHeure): static
    {
        $this->dateHeure = $dateHeure;

        return $this;
    }

    public function getScoreDomicile(): ?int
    {
        return $this->scoreDomicile;
    }

    public function setScoreDomicile(?int $scoreDomicile): static
    {
        $this->scoreDomicile = $scoreDomicile;

        return $this;
    }

    public function getScoreExterieur(): ?int
    {
        return $this->scoreExterieur;
    }

    public function setScoreExterieur(?int $scoreExterieur): static
    {
        $this->scoreExterieur = $scoreExterieur;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getPhase(): ?string
    {
        return $this->phase;
    }

    public function setPhase(string $phase): static
    {
        $this->phase = $phase;

        return $this;
    }

    public function getEquipeDomicile(): ?Equipe
    {
        return $this->equipeDomicile;
    }

    public function setEquipeDomicile(?Equipe $equipeDomicile): static
    {
        $this->equipeDomicile = $equipeDomicile;

        return $this;
    }

    public function getEquipeExterieur(): ?Equipe
    {
        return $this->equipeExterieur;
    }

    public function setEquipeExterieur(?Equipe $equipeExterieur): static
    {
        $this->equipeExterieur = $equipeExterieur;

        return $this;
    }

/**
     * @return Collection<int, Pronostic>
     */
    public function getPronostics(): Collection
    {
        return $this->pronostics;
    }

    public function addPronostic(Pronostic $pronostic): static
    {
        if (!$this->pronostics->contains($pronostic)) {
            $this->pronostics->add($pronostic);
            $pronostic->setRencontre($this);
        }

        return $this;
    }

    public function removePronostic(Pronostic $pronostic): static
    {
        if ($this->pronostics->removeElement($pronostic)) {
            // set the owning side to null (unless already changed)
            if ($pronostic->getRencontre() === $this) {
                $pronostic->setRencontre(null);
            }
        }

        return $this;
    }
}
