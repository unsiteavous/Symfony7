<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategorieFixtures extends Fixture
{
    private $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < sizeof(self::getCategorieArray()); $i++) {
            $categorie = new Category;
            $categorie->setName(self::getCategorieArray()[$i]);

            $manager->persist($categorie);

            $this->addReference($categorie->getName(), $categorie);
        }
        
        $manager->flush();
    }

    public static function getCategorieArray() {
        return ['Horreur', 'Science-Fiction', 'Romantisme', 'Documentaire', 'Walt Disney', 'Polar', 'Aventure'];
    }
}
