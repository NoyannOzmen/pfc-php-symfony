<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'images_animal')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'images_association')]
    private ?Association $association = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): static
    {
        $this->ordre = $ordre;

        return $this;
    }

    public function getAnimalId(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimalId(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getAssociationId(): ?Association
    {
        return $this->association;
    }

    public function setAssociationId(?Association $association): static
    {
        $this->association = $association;

        return $this;
    }
}
