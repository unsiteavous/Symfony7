<?php

namespace App\Controller;

use App\Entity\Classification;
use App\Form\ClassificationType;
use App\Repository\ClassificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/classification', name: 'app_classification_')]
class ClassificationController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(ClassificationRepository $cr): Response
    {
        $classifications = $cr->findAll();
        return $this->render('classification/index.html.twig', [
            'classifications' => $classifications,
        ]);
    }

    #[Route('/new', name: "new", methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClassificationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classification = $form->getData();
            $em->persist($classification);
            $em->flush();

            $this->addFlash('success', "La classification a bien été créée.");
            return $this->redirectToRoute('app_classification_index');
        }

        return $this->render('classification/form.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{intitule}', name: 'show', methods: ['GET'])]
    public function show(Classification $classification): Response
    {
        return $this->render('classification/show.html.twig', [
            'classification' => $classification,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['DELETE'])]
    public function delete(Classification $classification, EntityManagerInterface $em): Response
    {

        $em->remove($classification);
        $em->flush();

        $this->addFlash('success', "La classification a bien été supprimée.");
        return $this->redirectToRoute('app_classification_index');
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'PUT'])]
    public function edit(Classification $classification, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ClassificationType::class, $classification, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush($classification);

            $this->addFlash('success', "La classification a bien été modifiée.");
            return $this->redirectToRoute('app_classification_show', ['intitule' => $classification->getIntitule()]);
        }

        return $this->render('classification/form.html.twig', [
            'form'=> $form,
            'classification'=>$classification
        ]);
    }
}
