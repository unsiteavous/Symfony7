<?php

namespace App\Controller\API;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/film', name: 'api_film_')]
class FilmController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();
        return $this->json($films, context: ['groups' => 'api_film_index']);
    }

    #[Route('/{nom}', name: 'show')]
    public function show(Film $film): Response
    {
        return $this->json($film, context: ['groups' => ['api_film_index','api_film_show']]);
    }

    
}
