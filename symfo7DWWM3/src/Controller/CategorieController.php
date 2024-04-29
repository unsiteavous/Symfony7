<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/new', name: 'new', methods: ['GET', 'POST'], priority: 1)]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            $em->persist($categorie);
            $em->flush();

            $this->addFlash('success', "La catégorie a bien été ajoutée.");
            return $this-> redirectToRoute('app_categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{nom}/edit', name: 'edit', methods: ['GET', 'PUT'])]
    public function edit(Categorie $categorie, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie, ['method' => 'PUT']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $categorie = $form->getData();
            $em->flush();
            $this->addFlash('success', "La catégorie a été mise à jour.");
            return $this->redirectToRoute('app_categorie_index');
        }
        return $this->render('categorie/edit.html.twig', [
            'form' => $form,
            'categorie' => $categorie
        ]);
    }
}
