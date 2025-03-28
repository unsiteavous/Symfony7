<?php

namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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


    #[Route('/new', name: 'new', methods: ['GET', 'POST'], priority: 2)]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FilmType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $film = $form->getData();

            $entityManager->persist($film);
            $entityManager->flush();

            return $this->redirectToRoute('app_film_index');
        }

        return $this->render('film/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
