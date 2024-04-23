<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $urlAffiche = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienTrailer = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $resume = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSortie = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'films')]
    private Collection $categories;

    #[ORM\ManyToOne(inversedBy: 'films')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Classification $classification = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getUrlAffiche(): ?string
    {
        return $this->urlAffiche;
    }

    public function setUrlAffiche(string $urlAffiche): static
    {
        $this->urlAffiche = $urlAffiche;

        return $this;
    }

    public function getLienTrailer(): ?string
    {
        return $this->lienTrailer;
    }

    public function setLienTrailer(?string $lienTrailer): static
    {
        $this->lienTrailer = $lienTrailer;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getDuree(): ?\DateTimeInterface
    {
        return $this->duree;
    }

    public function setDuree(\DateTimeInterface $duree): static
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateSortie(): ?\DateTimeInterface
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeInterface $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    /**
     * @return Collection<int, categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getClassification(): ?Classification
    {
        return $this->classification;
    }

    public function setClassification(?Classification $classification): static
    {
        $this->classification = $classification;

        return $this;
    }
}
