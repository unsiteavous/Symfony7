<?php

namespace App\DataFixtures;

use App\Entity\Classification;
use Doctrine\Persistence\ObjectManager;

class ClassificationFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach ($this->getClassifications() as $name) {
            $classification = new Classification;
            $classification->setName($name);
            $classification->setDescription($this->faker->sentence());
            $manager->persist($classification);

            $this->addReference($name, $classification);
        }

        $manager->flush();
    }

    public static function getClassifications(): array
    {
        return [
            'Tout public',
            'Interdit aux - de 12 ans',
            'Interdit aux - de 16 ans',
            'Interdit aux - de 18 ans'
        ];
    }
}
