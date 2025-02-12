<?php

namespace App\Entity;

use App\Repository\FamilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FamilleRepository::class)]
class Famille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $rue = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $commune = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $code_postal = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $pays = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $hebergement = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $terrain = null;

    /**
     * @var Collection<int, Animal>
     */
    #[ORM\OneToMany(targetEntity: Animal::class, mappedBy: 'accueillant')]
    private Collection $animaux;

    /**
     * @var Collection<int, Demande>
     */
    #[ORM\OneToMany(targetEntity: Demande::class, mappedBy: 'demandes', orphanRemoval: true)]
    private Collection $demandes;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $accueillant = null;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
        $this->demandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getHebergement(): ?string
    {
        return $this->hebergement;
    }

    public function setHebergement(string $hebergement): static
    {
        $this->hebergement = $hebergement;

        return $this;
    }

    public function getTerrain(): ?string
    {
        return $this->terrain;
    }

    public function setTerrain(?string $terrain): static
    {
        $this->terrain = $terrain;

        return $this;
    }

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimaux(): Collection
    {
        return $this->animaux;
    }

    public function addAnimaux(Animal $animaux): static
    {
        if (!$this->animaux->contains($animaux)) {
            $this->animaux->add($animaux);
            $animaux->setAccueillant($this);
        }

        return $this;
    }

    public function removeAnimaux(Animal $animaux): static
    {
        if ($this->animaux->removeElement($animaux)) {
            // set the owning side to null (unless already changed)
            if ($animaux->getAccueillant() === $this) {
                $animaux->setAccueillant(null);
            }
        }

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
            $demande->setPotentiel_accueillant($this);
        }

        return $this;
    }

    public function removeDemande(Demande $demande): static
    {
        if ($this->demandes->removeElement($demande)) {
            // set the owning side to null (unless already changed)
            if ($demande->getPotentiel_accueillant() === $this) {
                $demande->setPotentiel_accueillant(null);
            }
        }

        return $this;
    }

    public function getAccueillant(): ?Utilisateur
    {
        return $this->accueillant;
    }

    public function setAccueillant(Utilisateur $accueillant): static
    {
        $this->accueillant = $accueillant;

        return $this;
    }
}
