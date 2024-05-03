<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(fields: ['titre', 'urlAffiche'], message: "Ce champ est déjà utilisé")]
#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('api_film_show')]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "Le titre ne peut pas rester vide.")]
    #[Assert\Length(min: 5, max: 255, minMessage: "Le titre doit comporter plus de 5 caractères.", maxMessage: "Le titre ne peut pas avoir plus de 255 caractères.")]
    #[Groups([
        'api_categorie_show',
        'api_classification_show',
        'api_film_index'
    ])]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[Assert\Url(message: "L'url n'est pas valide.")]
    #[Groups('api_film_index')]
    private ?string $urlAffiche = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('api_film_show')]
    private ?string $lienTrailer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('api_film_show')]
    private ?\DateTimeInterface $duree = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\LessThanOrEqual('+1 year', message:"La date de sortie doit être inférieure au {{ compared_value }}.")]
    #[Assert\GreaterThan('01-01-1800', message:"La date de sortie doit être supérieure au {{ compared_value }}.")]
    #[Groups('api_film_show')]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\ManyToOne(inversedBy: 'films')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('api_film_index')]
    private ?Classification $classification = null;

    /**
     * @var Collection<int, categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'films')]
    #[Groups('api_film_index')]
    private Collection $categorie;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

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

    public function getClassification(): ?Classification
    {
        return $this->classification;
    }

    public function setClassification(?Classification $classification): static
    {
        $this->classification = $classification;

        return $this;
    }

    /**
     * @return Collection<int, categorie>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Categorie $categorie): static
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Categorie $categorie): static
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }
}
