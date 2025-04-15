<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeanceFixtures extends Fixture implements DependentFixtureInterface 
{
    // private $faker;

    public function __construct()
    {
    //     $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
    //     for ($i = 0; $i < 10; $i++) {
    //         $seance = new Seance;
    //         $seance->setJour($this->faker->dateTime());
    //         $seance->setHeure($this->faker->dateTime());
    //         $seance->setPrix($this->faker->numberBetween(10, 50));
    //         $seance->setFilm($this->getReference('film_' . $this->faker->numberBetween(1, 10), Film::class));

    //         $manager->persist($seance);
    //       }
          
    //       $manager->flush();
    }

    public function getDependencies(): array {
      return [
        FilmFixtures::class,
        CategorieFixtures::class
      ];
    }

}
