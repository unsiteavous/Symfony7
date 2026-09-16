<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

    #[Route('/articles', name: 'app_')]
final class HomeController extends AbstractController
{
    #[Route('/bonjour', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            "prenom" => "Théophile",
        ]);
    }
}
