<?php

namespace App\Controller\Api;

use App\Entity\Classification;
use App\Repository\ClassificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/classification', name: 'api_classification_')]
class ClassificationController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(ClassificationRepository $cr): JsonResponse
    {
        $classifications = $cr->findAll();
        return $this->json($classifications, context:['groups' => 'api_classification_index']);
    }

    #[Route('/{intitule}', name:'show')]
    public function show(Classification $classification): JsonResponse
    {
        return $this->json($classification, context:['groups' => ['api_classification_index', 'api_classification_show']]);
    }
}
