<?php

namespace App\Controller;

use App\Entity\Classification;
use App\Form\ClassificationType;
use App\Repository\ClassificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/classification', name: 'app_classification_')]
final class ClassificationController extends AbstractController{

    #[Route('s/', name: 'index')]
    public function index(ClassificationRepository $classificationRepository): Response
    {
        $classifications = $classificationRepository->findAll();

        return $this->render('classification/index.html.twig', [
            'classifications' => $classifications,
        ]);
    }

    #[Route('/{name}', name: 'show', methods: ['GET'])]
    public function show(Classification $classification): Response
    {
        return $this->render('classification/show.html.twig', [
            'classification' => $classification,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'], priority:1)]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClassificationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classification = $form->getData();

            $entityManager->persist($classification);
            $entityManager->flush();

            return $this->redirectToRoute('app_classification_index');
        }

        return $this->render('classification/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/update/{id}', name: 'update', methods: ['GET', 'POST'], priority: 1)]
    public function udpate(Request $request, EntityManagerInterface $entityManager, Classification $classification): Response
    {
        $form = $this->createForm(ClassificationType::class, $classification);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classification = $form->getData();

            $entityManager->persist($classification);
            $entityManager->flush();

            return $this->redirectToRoute('app_classification_index');
        }

        return $this->render('classification/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


}
