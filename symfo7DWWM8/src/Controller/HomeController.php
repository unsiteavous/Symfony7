<?php

namespace App\Controller;

use PHPUnit\Util\Json;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use DateTime;

final class HomeController extends AbstractController{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route(path:'/about/{prenom}', name: 'app_about', methods: ['GET','POST'])]
    public function about($prenom): Response
    {
        return new Response(
            'Bonjour ' . $prenom 
        );
    }

    #[Route(path:'/about/index', name: 'app_about_index', methods: ['GET','POST'], priority: 1)]
    public function index_about(): Response
    {
        $data = [
            'prenom' => 'theo',
            'nom' => 'dupont'
        ];
        return new JsonResponse($data);
    }

    #[Route('/twig', name: 'app_twig')]
    public function twig(): Response
    {
        $user = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'age' => 32,
            'slogan' => '<center><b>Twig c\'est génial !</b></center>',
            'activated' => TRUE,
            'createdAt' => new DateTime('2020-12-21 15:27:30')
        ];

        dd($user);

        return $this->render('home/twig.html.twig', [
            'user' => $user,
        ]);
    }
}
