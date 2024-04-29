<?php

namespace App\Entity;

use App\Repository\ClassificationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity('intitule', message: "Cet intitulé est déjà utilisé.")]
#[ORM\Entity(repositoryClass: ClassificationRepository::class)]
class Classification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: 'Le nom ne doit pas être vide.')]
    #[Assert\Length(max: 100, min: 3, minMessage: "Le nom doit faire plus de 2 caractères", maxMessage: "Moins long Steupléééé")]
    private ?string $intitule = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "Moins long Steupléééé")]
    private ?string $avertissement = null;

    /**
     * @var Collection<int, Film>
     */
    #[ORM\OneToMany(targetEntity: Film::class, mappedBy: 'classification')]
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
