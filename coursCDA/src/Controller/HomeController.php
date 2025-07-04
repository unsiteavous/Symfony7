<?php

namespace App\Controller;

use LDAP\Result;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/', name: 'app_')]
final class HomeController extends AbstractController
{
    #[Route(path: '', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        $user = new stdClass();
        $user->prenom = 'Théophile';
        $user->dateNaissance = new \DateTime('2000-01-01');
        return $this->render('home/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: 'login', name: 'login', methods: ['GET'])]
    public function login(): Response
    {
        return new Response(' Page de login');
    }

    #[Route(path: 'twig', name: 'twig', methods: ['GET'])]
    public function twig(): Response
    {
        $user = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'age' => 32,
            'slogan' => '<center><b>Twig c\'est génial !</b></center>',
            'activated' => TRUE,
            'createdAt' => new \DateTime('2020-12-21 15:27:30')
        ];
        return $this->render('home/twig.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route(path: 'email', name: 'email', methods: ['GET'])]
    public function email(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('automate@unsiteavous.fr')
            ->to('contact@unsiteavous.fr')
            ->subject('Contact Unsiteavous.fr')
            ->text('format texte Contact Unsiteavous.fr')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return new Response('Email envoyé !');
    }
}
