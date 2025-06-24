<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[UniqueEntity(fields: ['name'], message: 'La catégorie existe déjà.')]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: 'Le nom de la catégorie ne doit pas être vide.')]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit avoir au moins {{ limit }} caractères. Actuellement, il y a {{ value|length }}.',
        maxMessage: 'Le nom doit avoir au plus {{ limit }} caractères. Actuellement, il y a {{ value|length }}.',
    )]
    private ?string $name = null;

    /**
     * @var Collection<int, Film>
     */
    #[ORM\ManyToMany(targetEntity: Film::class, mappedBy: 'categories')]
    private Collection $films;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Le nom doit avoir au moins {{ limit }} caractères. Actuellement, il y a {{ value|length }}.',
        maxMessage: 'Le nom doit avoir au plus {{ limit }} caractères. Actuellement, il y a {{ value|length }}.',
    )]
    private ?string $description = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $slug = null;

    public function __construct(
    ) {
        $this->films = new ArrayCollection();
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
        $this->name = ucfirst(strtolower($name));
        $this->setSlug($this->name);
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
            $film->addCategory($this);
        }

        return $this;
    }

    public function removeFilm(Film $film): static
    {
        if ($this->films->removeElement($film)) {
            $film->removeCategory($this);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
        return $this;
    }


}
