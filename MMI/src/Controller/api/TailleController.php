<?php

namespace App\Controller\api;

use App\Repository\TailleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/taille', name: 'api_taille_')]
final class TailleController extends AbstractController
{
    #[Route('s/', name: 'index')]
    public function index(TailleRepository $tailleRepository): Response
    {
        $tailles = $tailleRepository->findAll();
        return $this->json($tailles, 200, [], ['groups' => ['taille:list']]);
    }
}
