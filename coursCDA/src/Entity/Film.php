<?php

namespace App\Entity;

use App\Repository\FilmRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FilmRepository::class)]
class Film
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le titre ne peut pas être vide")]
    private ?string $name = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"La durée ne peut pas être vide")]
    private ?\DateTimeImmutable $duration = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'films')]
    private Collection $categories;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'url de l'affiche ne peut pas être vide")]
    private ?string $urlAffiche = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'url du trailer ne peut pas être vide")]
    private ?string $urlTrailer = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le résumé ne peut pas être vide")]
    private ?string $resume = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"La date de sortie ne peut pas être vide")]
    private ?\DateTimeImmutable $dateSortie = null;

    #[ORM\ManyToOne(inversedBy: 'films')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message:"La classification ne peut pas être vide")]
    private ?Classification $classification = null;

    #[ORM\Column(length: 50, unique: true)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getDuration(): ?\DateTimeImmutable
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeImmutable $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $Category): static
    {
        if (!$this->categories->contains($Category)) {
            $this->categories->add($Category);
        }

        return $this;
    }

    public function removeCategory(Category $Category): static
    {
        $this->categories->removeElement($Category);

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

    public function getUrlTrailer(): ?string
    {
        return $this->urlTrailer;
    }

    public function setUrlTrailer(string $urlTrailer): static
    {
        $this->urlTrailer = $urlTrailer;

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

    public function getDateSortie(): ?\DateTimeImmutable
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTimeImmutable $dateSortie): static
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
