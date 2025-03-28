<?php

namespace App\Controller;

use App\Entity\Classification;
use App\Repository\ClassificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
