<?php

namespace App\Controller;

use App\Entity\Classification;
use App\Form\ClassificationType;
use App\Repository\ClassificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/classification', name: 'app_classification_')]
class ClassificationController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(ClassificationRepository $classificationRepository): Response
    {
        $classifications = $classificationRepository->findAll();
        return $this->render('classification/index.html.twig', [
            'classifications' => $classifications,
        ]);
    }

    #[Route("/{intitule}", name: "show", methods: ['GET'])]
    public function show(Classification $classification) :Response
    {
        return $this->render('classification/show.html.twig', [
            'classification' => $classification
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET'], priority: 1)]
    public function new() : Response
    {
        $form = $this->createForm(ClassificationType::class);
        return $this->render('classification/new.html.twig', [
            'formNew' => $form
        ]);
    }
}
