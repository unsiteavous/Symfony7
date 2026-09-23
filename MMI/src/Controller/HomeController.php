<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

#[Route('', name: 'app_')]
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

    #[Route('/sendtestmail', name: 'sendtestmail')]
    public function sendTestMail(MailerInterface $mailer): Response
    {
        // L'expéditeur doit être l'adresse (ou le domaine) authentifiée sur le SMTP,
        // sinon SPF/DMARC font rejeter le mail ou l'envoient en spam.
        $email = (new Email())
            ->from('automate@epi-manager.fr')
            ->to('contact@unsiteavous.fr')
            ->subject('C\'est le temps du mail !')
            ->text('On envoie un mail en texte !')
            ->html('<p>Et voici un mail en <b>HTML</b> !</p>');

        try {
            // send() ne renvoie rien : un échec SMTP se traduit par une TransportException.
            // Si l'envoi passe par Messenger en async, cette exception n'est levée
            // qu'au moment où le worker traite le message.
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return $this->json(['message' => 'Mail non envoyé', 'erreur' => $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Mail envoyé']);
    }
}
