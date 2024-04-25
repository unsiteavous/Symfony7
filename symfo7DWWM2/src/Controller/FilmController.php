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

    #[Route('/new', name: "new", methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FilmType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $film = $form->getData();
            $em->persist($film);
            $em->flush();

            $this->addFlash('success', 'Le film est créé.');
            return $this->redirectToRoute('app_film_index');
        }

        return $this->render('film/form.html.twig', [
            'form' => $form
        ]);
    }

    #[Route("/{titre}", name: 'show', methods:['GET'])]
    public function show(Film $film)
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[Route('/{titre}', name: 'delete', methods:['DELETE'])]
    public function delete(Film $film, EntityManagerInterface $em): Response
    {
        $em->remove($film);
        $em->flush();

        $this->addFlash('success', 'Le film '.$film->getTitre().' a bien été supprimé.');
        return $this->redirectToRoute('app_film_index');
    }

    #[Route('/{titre}/edit', name:'edit', methods:['GET', 'PUT'])]
    public function edit(Film $film, Request $request, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(FilmType::class, $film, ['method'=> 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush($film);

            $this->addFlash('success', 'Le film a bien été mis à jour.');
            return $this->redirectToRoute('app_film_show', ['titre'=> $film->getTitre()]);
        }

        return $this->render('film/form.html.twig', [
            'form'=>$form,
            'film'=>$film
        ]);
    }
}
