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
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/', name: 'app_chaussure_')]
final class ChaussureController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(ChaussureRepository $chaussureRepository): Response
    {
        $chaussures = $chaussureRepository->findAll();
        return $this->render('chaussure/index.html.twig', [
            'chaussures' => $chaussures
        ]);
    }

    #[Route('chaussure/create', name: 'create', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN', statusCode: 423, message: "Vous n'avez pas les droits pour accéder à cette page")]
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

    #[Route('chaussure/{id}', name: 'show', methods: ['GET'])]
    public function show(?Chaussure $chaussure): Response
    {
        if (!$chaussure) {
            return $this->redirectToRoute('app_chaussure_index');
        }
        return $this->render('chaussure/show.html.twig', [
            'chaussure' => $chaussure
        ]);
    }

    #[Route('chaussure/{id}/update', name: 'update', methods: ['GET', 'POST'])]
    public function update(?Chaussure $chaussure, Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$chaussure) {
            return $this->redirectToRoute('app_chaussure_index');
        }
        $form = $this->createForm(CreateChaussureType::class, $chaussure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chaussure = $form->getData();
            $entityManager->flush();

            return $this->redirectToRoute('app_chaussure_index');
        }

        return $this->render('chaussure/update.html.twig', [
            'chaussure' => $chaussure,
            'form' => $form->createView()
        ], new Response(status: $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    #[Route('chaussure/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Chaussure $chaussure, EntityManagerInterface $entityManager): Response
    {
    if(!$chaussure) {
        return $this->redirectToRoute('app_chaussure_index');
    }
        $entityManager->remove($chaussure);
        $entityManager->flush();
        $this->addFlash('success', 'La chaussure a été supprimée');
        return $this->redirectToRoute('app_chaussure_index');
    }
}
