<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Classification;
use App\Entity\Film;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FilmTest extends KernelTestCase
{
    private EntityManager $manager;
    private $FilmRepo;
    private $CategoryRepo;
    private $ClassificationRepo;
    private $validator;
    public function setUp():void
    {
        $kernel = self::bootKernel();
        $this->manager = self::getContainer()->get('doctrine')->getManager();
        $this->FilmRepo = $this->manager->getRepository(Film::class);
        $this->CategoryRepo = $this->manager->getRepository(Category::class);
        $this->ClassificationRepo = $this->manager->getRepository(Classification::class);
        $this->validator = self::getContainer()->get('validator');
    }
    
    public function testFindAllFilms():void
    {
        $films = $this->FilmRepo->findAll();

        $this->assertCount(10, $films);
    }

    public function testCreateAFilm():void
    {
        $film = new film;
        $film->setTitre('Un titre élevé')
            ->setAffiche('URL de l\'affiche')
            ->setDateSortie(new DateTime)
            ->addCategory($this->CategoryRepo->findOneBy(['name' => 'Horreur']))
            ->setClassification($this->ClassificationRepo->findOneBy(['name' => 'Tout public']))
            ->setLienTrailer('URL du trailer')
            ->setDuree(new DateTime);

        $errors = $this->validator->validate($film);

        $this->assertCount(0, $errors);
        
        $this->manager->persist($film);
        $this->manager->flush();

        $this->assertIsInt($film->getId());
    }

    public function testCreateABadFilm():void
    {
        $film = new film;
        $film->setTitre('')
            ->setAffiche('')
            ->setDateSortie(new DateTime)
            ->addCategory($this->CategoryRepo->findOneBy(['name' => 'Horreur']))
            ->setClassification($this->ClassificationRepo->findOneBy(['name' => 'Tout public']))
            ->setLienTrailer('')
            ->setDuree(new DateTime);

        $errors = $this->validator->validate($film);

        $this->assertCount(3, $errors);
    }
}
