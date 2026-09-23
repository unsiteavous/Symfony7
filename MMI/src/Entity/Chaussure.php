<?php

namespace App\Entity;

use App\Repository\ChaussureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChaussureRepository::class)]
class Chaussure
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['chaussure:list', 'taille:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Le nom de la chaussure doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de la chaussure ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Groups(['chaussure:list', 'api_chaussure_new', 'api_chaussure_create'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Taille>
     */
    #[ORM\ManyToMany(targetEntity: Taille::class, inversedBy: 'chaussures')]
    #[Groups(['chaussure:list', 'api_chaussure_new'])]
    private Collection $taille;

    public function __construct()
    {
        $this->taille = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Taille>
     */
    public function getTaille(): Collection
    {
        return $this->taille;
    }

    public function addTaille(Taille $taille): static
    {
        if (!$this->taille->contains($taille)) {
            $this->taille->add($taille);
        }

        return $this;
    }

    public function removeTaille(Taille $taille): static
    {
        $this->taille->removeElement($taille);

        return $this;
    }

}
