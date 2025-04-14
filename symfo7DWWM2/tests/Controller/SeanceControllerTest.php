<?php

namespace App\Tests\Controller;

use App\Entity\Film;
use App\Entity\Seance;
use App\Repository\SeanceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SeanceControllerTest extends WebTestCase{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $seanceRepository;
    private string $path = '/seance/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->seanceRepository = $this->manager->getRepository(Seance::class);

        foreach ($this->seanceRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Seance index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        // $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'seance[jour]' => (new DateTime())->format('Y-m-d'),
            'seance[heure]' => (new DateTime())->format('H:i:s'),
            'seance[prix]' => 45,
            'seance[Film]' => 1,
        ]);

        var_dump($this->path);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->seanceRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Seance();
        $fixture->setJour('My Title');
        $fixture->setHeure('My Title');
        $fixture->setPrix('My Title');
        $fixture->setFilm('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Seance');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestSkipped();
        $fixture = new Seance();
        $fixture->setJour('Value');
        $fixture->setHeure('Value');
        $fixture->setPrix('Value');
        $fixture->setFilm('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'seance[jour]' => 'Something New',
            'seance[heure]' => 'Something New',
            'seance[prix]' => 'Something New',
            'seance[Film]' => 'Something New',
        ]);

        self::assertResponseRedirects('/seance/');

        $fixture = $this->seanceRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getJour());
        self::assertSame('Something New', $fixture[0]->getHeure());
        self::assertSame('Something New', $fixture[0]->getPrix());
        self::assertSame('Something New', $fixture[0]->getFilm());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Seance();
        $fixture->setJour('Value');
        $fixture->setHeure('Value');
        $fixture->setPrix('Value');
        $fixture->setFilm('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/seance/');
        self::assertSame(0, $this->seanceRepository->count([]));
    }
}
