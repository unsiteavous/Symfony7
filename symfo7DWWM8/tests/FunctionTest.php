<?php

namespace App\Tests;

use App\Entity\Film;
use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase
{
    public function testSomethingElse(): void
    {
        $this->assertTrue(true);
        $this->assertFalse(false);
    }

    public function testEnslugSomeString(): void
    {
        $film = new Film;
        $film->setTitre('Un Titre avec Espaces ');
        $this->assertSame('un-titre-avec-espaces', $film->getSlug());

        $film = new Film;
        $film->setTitre('Ô un éléphant dans la forêt');
        $this->assertSame('o-un-elephant-dans-la-foret', $film->getSlug());

        $film = new Film;
        $film->setTitre('<h1>Nouveau titre ! (copy)');
            $this->assertSame('nouveau-titre-copy', $film->getSlug());

        $film = new Film;
        $film->setTitre('Titre avec  2 espaces');
        $this->assertSame('titre-avec-2-espaces', $film->getSlug());

        $film = new Film;
        $film->setTitre('Titre avec   3 espaces');
        $this->assertSame('titre-avec-3-espaces', $film->getSlug());
    }
}
