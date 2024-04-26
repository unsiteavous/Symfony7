<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name:'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/twig', name:"app_twig", methods: ['GET'])]
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

        return $this->render('home/exo1.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/email', name: "app_email")]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('serveurtest@captp.fr')
            ->to('serveurtest@captp.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);

        return new Response('Le mail est envoyé');
    }
}
