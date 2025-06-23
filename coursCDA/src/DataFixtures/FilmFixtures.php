<?php

namespace App\DataFixtures;

use App\Entity\Classification;
use App\Entity\Category;
use App\Entity\Film;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class FilmFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $film = (new Film)
                ->setName($this->faker->words(3, true));

            $film
                ->setSlug($this->slugger->slug($film->getName(), '-', 'fr'))
                ->setDuration(DateTimeImmutable::createFromFormat("H:i:s", "1:56:30"))
                ->setUrlAffiche($this->faker->imageUrl())
                ->setUrlTrailer($this->faker->url())
                ->setResume($this->faker->text())
                ->setDateSortie(DateTimeImmutable::createFromFormat("Y-m-d", "2022-01-01"))
                ->setClassification($this->getReference(ClassificationFixtures::getClassifications()[$this->faker->numberBetween(0, 3)], Classification::class));

            for ($j = 0; $j < $this->faker->numberBetween(0, 5); $j++) {
                $film->addCategory($this->getReference(CategoryFixtures::getCategories()[$this->faker->numberBetween(0, 7)], Category::class));
            }

            $manager->persist($film);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClassificationFixtures::class,
            CategoryFixtures::class,
        ];
    }
}
