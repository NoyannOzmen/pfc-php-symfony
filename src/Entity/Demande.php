<?php

namespace App\Entity;

use App\Repository\DemandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandeRepository::class)]
class Demande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $statut_demande = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $date_debut = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $date_fin = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Famille $famille = null;

    #[ORM\ManyToOne(inversedBy: 'demandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animal $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut_demande(): ?string
    {
        return $this->statut_demande;
    }

    public function setStatut_demande(string $statut_demande): static
    {
        $this->statut_demande = $statut_demande;

        return $this;
    }

    public function getDate_debut(): ?string
    {
        return $this->date_debut;
    }

    public function setDate_debut(string $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDate_fin(): ?string
    {
        return $this->date_fin;
    }

    public function setDate_fin(string $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getPotentiel_accueillant(): ?Famille
    {
        return $this->famille;
    }

    public function setPotentiel_accueillant(?Famille $famille): static
    {
        $this->famille = $famille;

        return $this;
    }

    public function getAnimal_accueillable(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal_accueillable(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }
}
