<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[UniqueEntity(fields: 'nom', message: "La salle '{{ value }}' existe déjà.")]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom ne peut pas rester vide.")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\Positive(message:"{{ value }} n'est pas pas correct. Le nombre de place est forcément positif.")]
    #[Assert\NotBlank(message: "Le nombre de places doit être renseigné.")]
    private ?int $places = null;

    #[ORM\Column]
    private ?bool $accessibilite = null;

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

    public function getPlaces(): ?int
    {
        return $this->places;
    }

    public function setPlaces(int $places): static
    {
        $this->places = $places;

        return $this;
    }

    public function isAccessibilite(): ?bool
    {
        return $this->accessibilite;
    }

    public function setAccessibilite(bool $accessibilite): static
    {
        $this->accessibilite = $accessibilite;

        return $this;
    }
}
