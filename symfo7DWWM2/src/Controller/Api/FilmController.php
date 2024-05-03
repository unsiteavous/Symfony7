<?php

namespace App\Controller\Api;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/film', name: 'api_film_')]
class FilmController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(FilmRepository $fr): JsonResponse
    {
        $films = $fr->findAll();
        return $this->json($films, context:['groups' => 'api_film_index']);
    }

    #[Route('/{titre}', name:"show")]
    public function show(Film $film): JsonResponse
    {
        return $this->json($film, context: ['groups' => ['api_film_index', 'api_film_show']]);
    }
}
