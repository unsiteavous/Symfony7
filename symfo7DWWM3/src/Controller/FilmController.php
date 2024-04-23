<?php

namespace App\Controller;

use App\Entity\Film;
use App\Repository\FilmRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/film', name: "app_film_")]
class FilmController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();
        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route('/{id}', name:'show', methods:['GET'], requirements: ['id' => '\d+'])]
    public function show(Film $film): Response
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/new', name:"new", methods:['GET'])]
    public function new(){
        return $this->render('film/new.html.twig');
    }
}
