<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/film', name: 'app_film_')]
final class FilmController extends AbstractController{

    #[Route('s/', name: 'index')]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();
        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route('/{titre}', name: 'show', methods: ['GET'])]
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]); 
    }

    #[Route('/duree/{duration}', name: 'duree', methods: ['GET'], priority: 1)]
    public function duree(FilmRepository $filmRepository, string $duration): Response
    {
        $films = $filmRepository->findAllFilmsWithDurationGreaterThan($duration);

        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]); 
    }

    #[Route('/date/', name: 'date', methods: ['GET'], priority: 1)]
    public function date(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAllFilmsWithDateGreaterThanOneMonth();

        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]); 
    }
}
