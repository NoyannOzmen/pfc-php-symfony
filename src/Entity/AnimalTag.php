<?php

namespace App\Entity;

use App\Repository\AnimalTagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalTagRepository::class)]
class AnimalTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tags')]
    private ?Animal $animal = null;

    #[ORM\ManyToOne(inversedBy: 'tags')]
    private ?Tag $tag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnimal(): ?Animal
    {
        return $this->animal;
    }

    public function setAnimal(?Animal $animal): static
    {
        $this->animal = $animal;

        return $this;
    }

    public function getTags(): ?Tag
    {
        return $this->tag;
    }

    public function setTags(?Tag $tag): static
    {
        $this->tag = $tag;

        return $this;
    }
}
