<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\NoSuspiciousCharacters;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
#[UniqueEntity('nom', message: "Ce nom est déjà pris.")]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'api_categorie_index'
        ])]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message:"Un nom est requis.")]
    #[Assert\NoSuspiciousCharacters(checks:NoSuspiciousCharacters::CHECK_INVISIBLE,restrictionLevel: NoSuspiciousCharacters::RESTRICTION_LEVEL_HIGH )]
    #[Assert\Length(min: 2, minMessage:"Le nom doit contenir au minimum 2 caractères.", max: 255, maxMessage:"Moins long steuplé")]
    #[Groups([
        'api_categorie_index',
        'api_film_index',
        'api_categorie_new'
    ])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups([
        'api_categorie_show',
        'api_categorie_new'
    ])]
    private ?string $description = null;

    /**
     * @var Collection<int, Film>
     */
    #[ORM\ManyToMany(targetEntity: Film::class, mappedBy: 'categorie')]
    #[Groups(['api_categorie_show'])]
    private Collection $films;

    public function __construct()
    {
        $this->films = new ArrayCollection();
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
     * @return Collection<int, Film>
     */
    public function getFilms(): Collection
    {
        return $this->films;
    }

    public function addFilm(Film $film): static
    {
        if (!$this->films->contains($film)) {
            $this->films->add($film);
            $film->addCategorie($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): static
    {
        if ($this->films->removeElement($film)) {
            $film->removeCategorie($this);
        }

        return $this;
    }
}
