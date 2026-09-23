<?php

namespace App\Entity;

use App\Repository\TailleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TailleRepository::class)]
class Taille
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['taille:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    #[Groups(['chaussure:list','taille:list'])]
    private ?string $intitule = null;

    /**
     * @var Collection<int, Chaussure>
     */
    #[ORM\ManyToMany(targetEntity: Chaussure::class, mappedBy: 'taille')]
    #[Groups(['taille:list'])]
    private Collection $chaussures;

    public function __construct()
    {
        $this->chaussures = new ArrayCollection();
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

    /**
     * @return Collection<int, Chaussure>
     */
    public function getChaussures(): Collection
    {
        return $this->chaussures;
    }

    public function addChaussure(Chaussure $chaussure): static
    {
        if (!$this->chaussures->contains($chaussure)) {
            $this->chaussures->add($chaussure);
            $chaussure->addTaille($this);
        }

        return $this;
    }

    public function removeChaussure(Chaussure $chaussure): static
    {
        if ($this->chaussures->removeElement($chaussure)) {
            $chaussure->removeTaille($this);
        }

        return $this;
    }
}
