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

#[Route('/categorie', name: "app_categorie_")]
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

  #[Route('/{id}', name: "show", methods: ['GET'], requirements: ['id' => '\d+'])]
  public function show(Categorie $categorie)
  {
    return $this->render('categorie/show.html.twig', [
      'categorie' => $categorie
    ]);
  }

  #[Route('/new', name: "new", methods: ['GET', 'POST'])]
  public function new(Request $request, EntityManagerInterface $em): Response
  {
    $form = $this->createForm(CategorieType::class);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $categorie = $form->getData();
      $em->persist($categorie);
      $em->flush();

      $this->addFlash('success', "La catégorie a bien été enregistrée.");
      return $this->redirectToRoute('app_categorie_index');
    }

    return $this->render('categorie/form.html.twig', [
      'form' => $form
    ]);
  }


  #[Route('/{id}/delete', name: "delete", methods: ['DELETE'])]
  public function delete(Categorie $categorie, EntityManagerInterface $em): Response
  {
    $em->remove($categorie);
    $em->flush();

    $this->addFlash('success', "La catégorie a bien été supprimée.");
    return $this->redirectToRoute('app_categorie_index');
  }

  #[Route('/{id}/edit', name:'edit', methods:['GET', 'PUT'])]
  public function edit(Categorie $categorie, Request $request, EntityManagerInterface $em) : Response 
  {
    
    $form = $this->createForm(CategorieType::class, $categorie, ['method' => 'PUT']);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush($categorie);

      $this->addFlash('success', 'Modification effectuée !');
      return $this->redirectToRoute('app_categorie_show', ['id'=> $categorie->getId()]);
    }

    return $this->render('categorie/form.html.twig', [
      'form' => $form,
      'categorie' => $categorie
    ]);
  }

}
