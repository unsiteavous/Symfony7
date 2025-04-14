<?php

namespace App\Tests;

use App\Entity\Film;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FilmTest extends KernelTestCase
{
    private $manager;
    private $repository;
    public function setUp(): void
    {
        self::bootKernel();
        $this->manager = self::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Film::class);
    }

    public function testFindAllFilms(): void
    {
        
        $films = $this->repository->findAll();

        // on en a mis 10 avec les fixtures
        $this->assertCount(10, $films);
    }
}
