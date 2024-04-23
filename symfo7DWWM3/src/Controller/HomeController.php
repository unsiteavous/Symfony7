<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'prenom' => 'Théophile',
        ]);
    }
    
    
    #[Route('/bienvenue/{prenom}', name: 'app_bienvenue', methods:['GET'])]
    public function bienvenue($prenom)
    {
        return $this->render('home/index.html.twig', [
            'prenom' => $prenom
        ]);
    }

    #[Route('/twig', name:'app_twig')]
    public function twig(){
        $user = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'age' => 32,
            'slogan' => '<center><b>Twig c\'est génial !</b></center>',
            'activated' => TRUE,
            'createdAt' => new \DateTime('2020-12-21 15:27:30')
        ];

        return $this->render('home/twig.html.twig', [
            'user' => $user
        ]);
    }

}
