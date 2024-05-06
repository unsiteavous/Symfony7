<?php

namespace App\DataFixtures;

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
        ->setNom($this->faker->words(2, true))
        ->setDuree($this->faker->dateTimeBetween('-9 hour', '-5 hours'))
        ->setDateSortie($this->faker->dateTimeBetween('28/18/1895', '+1 year'))
        ->setUrlAffiche($this->faker->url())
        ->setLienTrailer($this->faker->url())
        ->setResume($this->faker->text(300))
        ->setClassification($this->getReference($this->faker->randomElement(ClassificationFixtures::getClassificationArray())));

      $limite = $this->faker->numberBetween(0, 5);
      for ($j = 0; $j < $limite; $j++) {
        $categoriesUtilisees = [];
        foreach ($film->getCategories() as $categorie) {
          $categoriesUtilisees[] = $categorie->getNom();
        }
        $categoriesNonUtilisees = array_diff(CategorieFixtures::getCategorieArray(), $categoriesUtilisees);

        $categorieReference = $this->faker->randomElement($categoriesNonUtilisees);
        $film->addCategory($this->getReference($categorieReference));
      }

      $manager->persist($film);
    }

    $manager->flush();
  }

  public function getDependencies()
  {
    return [ClassificationFixtures::class, CategorieFixtures::class];
  }
}
