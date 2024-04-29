<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: 'nom', message: 'Ce nom est déjà utilisé.')]
#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Length(min: 2, max: 100, minMessage: "Le nom du film doit avoir 2 caractères minimum.", maxMessage: "Moins long steuplé !")]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'url de l'affiche ne peut pas être vide.")]
    #[Assert\Url(message: "Merci d'entrer une url valide.")]
    private ?string $urlAffiche = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lienTrailer = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: "Le résumé ne peut pas être vide.")]
    private ?string $resume = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La durée ne peut pas être vide.")]
    #[Assert\LessThan("01-01-1970 10:00:00", message: "La durée doit être inférieure à 10h.")]
    #[Assert\GreaterThanOrEqual("01-01-1970 00:03:00", message: "La durée doit être supérieure ou égale à 3min.")]
    private ?\DateTimeInterface $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date de sortie ne peut pas être vide.")]
    #[Assert\GreaterThan("28-12-1895 00:00:00", message: "La date de sortie ne peut pas etre antérieure au 28/12/1895.")]
    #[Assert\LessThan("+1 year", message: "La date de sortie ne peut dépasser {{ compared_value }}.")]
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
