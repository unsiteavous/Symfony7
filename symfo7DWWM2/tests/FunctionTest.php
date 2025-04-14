<?php

namespace App\Tests;

use App\Entity\Film;
use PHPUnit\Framework\TestCase;

class FunctionTest extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }

    public function testEnslugTitle(): void
    {
        $film = new Film;
        $film->setTitre('un titre simple');
        $this->assertEquals('un-titre-simple', $film->getSlug());

        $film = new Film;
        $film->setTitre('ô titre accentué');
        $this->assertEquals('o-titre-accentue', $film->getSlug());

        $film = new Film;
        $film->setTitre('titre (copy)');
        $this->assertEquals('titre-copy', $film->getSlug());
    }
}
