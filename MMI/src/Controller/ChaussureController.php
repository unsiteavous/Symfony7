<?php

namespace App\Controller;

use App\Entity\Chaussure;
use App\Form\CreateChaussureType;
use App\Repository\ChaussureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/chaussure', name: 'app_chaussure_')]
final class ChaussureController extends AbstractController
{
    #[Route('s/', name: 'index')]
    public function index(ChaussureRepository $chaussureRepository): Response
    {
        $chaussures = $chaussureRepository->findAll();
        return $this->render('chaussure/index.html.twig', [
            'chaussures' => $chaussures
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET', 'POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CreateChaussureType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chaussure = $form->getData();
            $entityManager->persist($chaussure);
            $entityManager->flush();

            return $this->redirectToRoute('app_chaussure_index');
        }

        return $this->render('chaussure/create.html.twig', [
            'form' => $form->createView()
        ], new Response(status: $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }
}
