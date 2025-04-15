<?php

namespace App\Tests;

use App\Entity\Film;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FilmTest extends KernelTestCase
{
    private EntityManager $manager;
    private $repository;
    public function setUp():void
    {
        $kernel = self::bootKernel();
        $this->manager = self::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Film::class);
    }
    
    public function testFindAllFilms():void
    {
        $films = $this->repository->findAll();

        $this->assertCount(10, $films);
    }
}
