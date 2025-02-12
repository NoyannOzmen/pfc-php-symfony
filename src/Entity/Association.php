<?php

namespace App\Entity;

use App\Repository\AssociationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssociationRepository::class)]
class Association
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $responsable = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rue = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commune = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $code_postal = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $siret = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $site = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'association')]
    private Collection $images_association;

    /**
     * @var Collection<int, Animal>
     */
    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'association', orphanRemoval: true)]
    private Collection $pensionnaires;

    /* #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null; */

    #[ORM\OneToOne(targetEntity: Utilisateur::class, inversedBy: 'refuge')]
    #[ORM\JoinColumn(name: 'utilisateur_id', referencedColumnName: 'id')]
    private Utilisateur|null $utilisateur_id = null;

    public function __construct()
    {
        $this->images_association = new ArrayCollection();
        $this->pensionnaires = new ArrayCollection();
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

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): static
    {
        $this->rue = $rue;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): static
    {
        $this->commune = $commune;

        return $this;
    }

    public function getCode_postal(): ?string
    {
        return $this->code_postal;
    }

    public function setCode_postal(string $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): static
    {
        $this->siret = $siret;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSite(): ?string
    {
        return $this->site;
    }

    public function setSite(?string $site): static
    {
        $this->site = $site;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getImages_association(): Collection
    {
        return $this->images_association;
    }

    public function addImages_association(Media $images_association): static
    {
        if (!$this->images_association->contains($images_association)) {
            $this->images_association->add($images_association);
            $images_association->setAssociationId($this);
        }

        return $this;
    }

    public function removeImages_association(Media $images_association): static
    {
        if ($this->images_association->removeElement($images_association)) {
            // set the owning side to null (unless already changed)
            if ($images_association->getAssociationId() === $this) {
                $images_association->setAssociationId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getPensionnaires(): Collection
    {
        return $this->pensionnaires;
    }

    public function addPensionnaire(Animal $pensionnaire): static
    {
        if (!$this->pensionnaires->contains($pensionnaire)) {
            $this->pensionnaires->add($pensionnaire);
            $pensionnaire->setRefuge($this);
        }

        return $this;
    }

    public function removePensionnaire(Animal $pensionnaire): static
    {
        if ($this->pensionnaires->removeElement($pensionnaire)) {
            // set the owning side to null (unless already changed)
            if ($pensionnaire->getRefuge() === $this) {
                $pensionnaire->setRefuge(null);
            }
        }

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur_id;
    }

    public function setUtilisateur(Utilisateur $utilisateur_id): static
    {
        $this->utilisateur_id = $utilisateur_id;

        return $this;
    }
}
