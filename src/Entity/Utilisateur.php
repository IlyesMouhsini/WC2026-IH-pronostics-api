<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    #[ORM\Column(length: 255)]
    private ?string $motDePasse = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, Pronostic>
     */
    #[ORM\OneToMany(targetEntity: Pronostic::class, mappedBy: 'utilisateur')]
    private Collection $pronostics;

    public function __construct()
    {
        $this->pronostics = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getMotDePasse(): ?string
    {
        return $this->motDePasse;
    }

    public function setMotDePasse(string $motDePasse): static
    {
        $this->motDePasse = $motDePasse;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

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
            $pronostic->setUtilisateur($this);
        }

        return $this;
    }

    public function removePronostic(Pronostic $pronostic): static
    {
        if ($this->pronostics->removeElement($pronostic)) {
            // set the owning side to null (unless already changed)
            if ($pronostic->getUtilisateur() === $this) {
                $pronostic->setUtilisateur(null);
            }
        }

        return $this;
    }
}
