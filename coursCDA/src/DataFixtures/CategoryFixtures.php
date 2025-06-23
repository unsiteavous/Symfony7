<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getCategories() as $name) {
            $category = new Category;
            $category->setName($name);
            $category->setDescription($this->faker->sentence(10, true));
            $manager->persist($category);

            $this->addReference($name, $category);
        }

        $manager->flush();
    }

    public static function getCategories(): array {
        return [
            'Horreur',
            'Fantastique',
            'Drame',
            'Comédie',
            'Science-fiction',
            'Historique',
            'Documentaire',
            'Dessin animé'
        ];
    }
}
