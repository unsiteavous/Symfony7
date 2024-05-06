<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
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
            $categorie = new Categorie;
            $categorie->setNom(self::getCategorieArray()[$i]);
            $categorie->setDescription($this->faker->sentence(7));
            
            $manager->persist($categorie);
            $this->addReference($categorie->getNom(), $categorie);
        }

        $manager->flush();
    }

    public static function getCategorieArray(){
        return ['Polar', 'Science-Fiction', 'Romantique', 'Historique', 'Manga', 'Walt Disney', 'Drame', 'Horreur', 'Fantastique', 'Comédie'];
    }
}
