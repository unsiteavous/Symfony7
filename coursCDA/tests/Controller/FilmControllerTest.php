<?php

namespace App\Tests\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FilmControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $filmRepository;
    private string $path = '/film/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->filmRepository = $this->manager->getRepository(Film::class);

        foreach ($this->filmRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Film index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'film[name]' => 'Testing',
            'film[duration]' => 'Testing',
            'film[urlAffiche]' => 'Testing',
            'film[urlTrailer]' => 'Testing',
            'film[resume]' => 'Testing',
            'film[dateSortie]' => 'Testing',
            'film[categories]' => 'Testing',
            'film[classification]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->filmRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Film();
        $fixture->setName('My Title');
        $fixture->setDuration('My Title');
        $fixture->setUrlAffiche('My Title');
        $fixture->setUrlTrailer('My Title');
        $fixture->setResume('My Title');
        $fixture->setDateSortie('My Title');
        $fixture->setCategories('My Title');
        $fixture->setClassification('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Film');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Film();
        $fixture->setName('Value');
        $fixture->setDuration('Value');
        $fixture->setUrlAffiche('Value');
        $fixture->setUrlTrailer('Value');
        $fixture->setResume('Value');
        $fixture->setDateSortie('Value');
        $fixture->setCategories('Value');
        $fixture->setClassification('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'film[name]' => 'Something New',
            'film[duration]' => 'Something New',
            'film[urlAffiche]' => 'Something New',
            'film[urlTrailer]' => 'Something New',
            'film[resume]' => 'Something New',
            'film[dateSortie]' => 'Something New',
            'film[categories]' => 'Something New',
            'film[classification]' => 'Something New',
        ]);

        self::assertResponseRedirects('/film/');

        $fixture = $this->filmRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getName());
        self::assertSame('Something New', $fixture[0]->getDuration());
        self::assertSame('Something New', $fixture[0]->getUrlAffiche());
        self::assertSame('Something New', $fixture[0]->getUrlTrailer());
        self::assertSame('Something New', $fixture[0]->getResume());
        self::assertSame('Something New', $fixture[0]->getDateSortie());
        self::assertSame('Something New', $fixture[0]->getCategories());
        self::assertSame('Something New', $fixture[0]->getClassification());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Film();
        $fixture->setName('Value');
        $fixture->setDuration('Value');
        $fixture->setUrlAffiche('Value');
        $fixture->setUrlTrailer('Value');
        $fixture->setResume('Value');
        $fixture->setDateSortie('Value');
        $fixture->setCategories('Value');
        $fixture->setClassification('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/film/');
        self::assertSame(0, $this->filmRepository->count([]));
    }
}
