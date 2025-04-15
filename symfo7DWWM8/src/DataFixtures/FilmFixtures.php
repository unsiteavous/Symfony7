<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Category;
use App\Entity\Classification;
use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture implements DependentFixtureInterface 
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $film = new Film;
            $film
              ->setId($i + 1)
              ->setTitre($this->faker->sentence($this->faker->randomDigitNotNull(6)))
              ->setAffiche($this->faker->url())
              ->setLienTrailer($this->faker->url())
              ->setDuree($this->faker->dateTimeBetween('0 hour', "9 hours"))
              ->setDateSortie($this->faker->dateTimeBetween('-54 years', "+1 year"))
              ->setClassification($this->getReference($this->faker->randomElement(ClassificationFixtures::getClassificationArray()), Classification::class))
              ->addCategory($this->getReference($this->faker->randomElement(CategorieFixtures::getCategorieArray()), Category::class))
            ;

            $this->addReference('film_' . $film->getId(), $film);

            $manager->persist($film);
          }
          
          $manager->flush();
    }

    public function getDependencies(): array {
      return [
        ClassificationFixtures::class,
        CategorieFixtures::class
      ];
    }

}
