<?php

namespace App\Entity;

use App\Repository\ClassificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClassificationRepository::class)]
#[UniqueEntity('intitule', message: "Cet intitulé existe déjà.")]
class Classification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('api_classification_index')]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: "L'intitulé ne peut pas rester vide.")]
    #[Assert\Length(min: 5, max: 255, minMessage: "L'intitulé doit comporter plus de 5 caractères.", maxMessage: "L'intitulé ne peut pas avoir plus de 255 caractères.")]
    #[Groups([
        'api_classification_index',
        'api_film_index'
    ])]
    private ?string $intitule = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('api_classification_show')]
    private ?string $avertissement = null;

    /**
     * @var Collection<int, Film>
     */
    #[ORM\OneToMany(targetEntity: Film::class, mappedBy: 'classification')]
    #[Groups('api_classification_show')]
    private Collection $films;

    public function __construct()
    {
        $this->films = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    public function getAvertissement(): ?string
    {
        return $this->avertissement;
    }

    public function setAvertissement(?string $avertissement): static
    {
        $this->avertissement = $avertissement;

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
            $film->setClassification($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): static
    {
        if ($this->films->removeElement($film)) {
            // set the owning side to null (unless already changed)
            if ($film->getClassification() === $this) {
                $film->setClassification(null);
            }
        }

        return $this;
    }
}
