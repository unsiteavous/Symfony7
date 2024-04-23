<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class FilmController extends AbstractController
{
    #[Route('/films', name: 'app_film_index')]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();
        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route("/film/{id}", name: 'app_film_show')]
    public function show($id, FilmRepository $filmRepository)
    {
        $film = $filmRepository->find($id);

        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }
}
