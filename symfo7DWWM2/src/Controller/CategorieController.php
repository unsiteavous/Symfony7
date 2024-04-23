<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/categorie', name: "app_categorie")]
class CategorieController extends AbstractController
{
    #[Route('s', name: '_index')]
    public function index(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        return $this->render('categorie/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{id}', name:"_show", methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Categorie $categorie)
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie
        ]);
    }

    #[Route('/{id}/edit', name: "_edit", methods: ['GET'])]
    public function edit($id, CategorieRepository $categorieRepository)
    {
        return $this->render('categorie/edit.html.twig', [
            'id' => $id
        ]);
    }

    #[Route('/{id}/edit', name: "_update", methods: ['POST'])]
    public function update($id, CategorieRepository $categorieRepository)
    {
        // traitement
    }

    #[Route('/new', name: "_new", methods: ['GET'])]
    public function new()
    {
        return $this->render('categorie/new.html.twig');
    }

    #[Route('/new', name: "_create", methods: ['POST'])]
    public function create()
    {
        // traitement
    }


}
