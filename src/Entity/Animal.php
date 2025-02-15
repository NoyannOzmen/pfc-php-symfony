<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $race = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $couleur = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $sexe = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $statut = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'animal')]
    private Collection $images_animal;

    #[ORM\ManyToOne(inversedBy: 'pensionnaires', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Association $association = null;

    #[ORM\ManyToOne(inversedBy: 'representants', fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Espece $espece = null;

    #[ORM\ManyToOne(inversedBy: 'animaux')]
    private ?Famille $famille = null;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'animal', fetch: "EAGER")]
    private Collection $demandes;

    /**
     * @var Collection<int, AnimalTag>
     */
    #[ORM\OneToMany(targetEntity: AnimalTag::class, mappedBy: 'animal')]
    private Collection $tags;

    public function __construct()
    {
        $this->demandes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getRace(): ?string
    {
        return $this->race;
    }

    public function setRace(?string $race): static
    {
        $this->race = $race;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Media>
     */
    public function getImages_animal(): Collection
    {
        return $this->images_animal;
    }

    public function addImages_animal(Media $images_animal): static
    {
        if (!$this->images_animal->contains($images_animal)) {
            $this->images_animal->add($images_animal);
            $images_animal->setAnimalId($this);
        }

        return $this;
    }

    public function removeImages_animal(Media $imagesAnimal): static
    {
        if ($this->images_animal->removeElement($imagesAnimal)) {
            // set the owning side to null (unless already changed)
            if ($imagesAnimal->getAnimalId() === $this) {
                $imagesAnimal->setAnimalId(null);
            }
        }

        return $this;
    }

    public function getRefuge(): ?Association
    {
        return $this->association;
    }

    public function setRefuge(?Association $association): static
    {
        $this->association = $association;

        return $this;
    }

    public function getEspece(): ?Espece
    {
        return $this->espece;
    }

    public function setEspece(?Espece $espece): static
    {
        $this->espece = $espece;

        return $this;
    }

    public function getFamille(): ?Famille
    {
        return $this->famille;
    }

    public function setFamille(?Famille $famille): static
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * @return Collection<int, Demande>
     */
    public function getDemandes(): Collection
    {
        return $this->demandes;
    }

    public function addDemande(Demande $demande): static
    {
        if (!$this->demandes->contains($demande)) {
            $this->demandes->add($demande);
            $demande->setAnimal_accueillable($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getAnimal_accueillable() === $this) {
                $demande->setAnimal_accueillable(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AnimalTag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(AnimalTag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->setAnimal($this);
        }

        return $this;
    }

    public function removeTag(AnimalTag $tag): static
    {
        if ($this->tags->removeElement($tag)) {
            // set the owning side to null (unless already changed)
            if ($tag->getAnimal() === $this) {
                $tag->setAnimal(null);
            }
        }

        return $this;
    }
}
