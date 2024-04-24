<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorie', name: 'app_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{nom}', name: 'show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        $films = $categorie->getFilms();

        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
            'films' => $films
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET'], priority: 1)]
    public function new(): Response
    {
        $form = $this->createForm(CategorieType::class);
        return $this->render('categorie/new.html.twig', [
            'form' => $form
        ]);
    }
}
