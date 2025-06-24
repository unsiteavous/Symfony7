<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class SluggerService
{
    private $slugger;
    private $em;

    public function __construct(SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $this->slugger = $slugger;
        $this->em = $em;
    }

    /**
     * Create a unique slug.
     *
     * @param string $name string to convert to slug
     * @param string $class class name of the entity
     * @return string $slug
     */
    public function slug(string $name, $class): string
    {
        $slug = $this->slugger->slug($name, '-', 'fr')->lower();
        return $this->slugVerify($slug, $class);
    }

    private function slugVerify(string $originalSlug, string $class): string
    {
        $category = $this->em->getRepository($class::class)->findOneBy(['slug' => $originalSlug]);
        if ($category === null) {
            return $originalSlug;
        }

        $slug = $originalSlug . '-' . 1;
        $turn = 1;
        while ($this->em->getRepository($class::class)->findOneBy(['slug' => $slug]) !== null) {
            $turn++;
            $slug = $originalSlug . '-' . $turn;
        }

        return $slug;
    }
}
