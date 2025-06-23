<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

abstract class AbstractFixtures extends Fixture
{
    protected $faker;
    public function __construct( protected SluggerInterface $slugger)
    {
        $this->faker = Factory::create('fr_FR');
    }
}
