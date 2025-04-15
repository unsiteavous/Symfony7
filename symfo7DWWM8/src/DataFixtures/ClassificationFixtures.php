<?php

namespace App\DataFixtures;

use App\Entity\Classification;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ClassificationFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < sizeof(self::getClassificationArray()); $i++) {
            $classification = new Classification;
            $classification->setName(self::getClassificationArray()[$i]);
            $classification->setAvertissement($this->faker->sentence());

            $this->addReference($classification->getName(), $classification);

            $manager->persist($classification);
          }
          
          $manager->flush();
    }

    public static function getClassificationArray() {
      return ['Tout public', 'Interdit aux - de 12 ans', 'Interdit aux - de 16 ans', 'Interdit aux - de 18 ans'];
    }
}
