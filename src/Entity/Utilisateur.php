<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $mot_de_passe = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Association $refuge = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Famille $accueillant = null;

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

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): static
    {
        $this->mot_de_passe = $mot_de_passe;

        return $this;
    }

    public function getRefuge(): ?Association
    {
        return $this->refuge;
    }

    public function setRefuge(?Association $refuge): static
    {
        $this->refuge = $refuge;

        return $this;
    }

    public function getAccueillant(): ?Famille
    {
        return $this->accueillant;
    }

    public function setAccueillant(?Famille $accueillant): static
    {
        $this->accueillant = $accueillant;

        return $this;
    }
}
