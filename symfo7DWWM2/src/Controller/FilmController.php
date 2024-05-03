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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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

    #[IsGranted('ROLE_USER', message: "Tu n'as rien à faire là.")]
    #[Route('/new', name: "new", methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        try {
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
        } catch (AccessDeniedException $e) {
            // Récupérez le message d'accès refusé
            $errorMessage = $e->getMessage();

            // Vous pouvez maintenant utiliser $errorMessage comme bon vous semble, par exemple le transmettre à votre vue
            return new Response($errorMessage, 403);
        }
    }

    #[Route("/{titre}", name: 'show', methods: ['GET'])]
    public function show(Film $film)
    {
        return $this->render('film/show.html.twig', [
            'film' => $film,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/{titre}', name: 'delete', methods: ['DELETE'])]
    public function delete(Film $film, EntityManagerInterface $em): Response
    {
        $em->remove($film);
        $em->flush();

        $this->addFlash('success', 'Le film ' . $film->getTitre() . ' a bien été supprimé.');
        return $this->redirectToRoute('app_film_index');
    }

    #[IsGranted('ROLE_USER', message: "Tu ne peux pas modifier")]
    #[Route('/{titre}/edit', name: 'edit', methods: ['GET', 'PUT'])]
    public function edit(Film $film, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(FilmType::class, $film, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush($film);

            $this->addFlash('success', 'Le film a bien été mis à jour.');
            return $this->redirectToRoute('app_film_show', ['titre' => $film->getTitre()]);
        }

        return $this->render('film/form.html.twig', [
            'form' => $form,
            'film' => $film
        ]);
    }
}
