<?php

namespace App\Controller;

use App\Entity\Classification;
use App\Form\ClassificationForm;
use App\Repository\ClassificationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/classification', name: 'app_classification_')]
final class ClassificationController extends AbstractController
{
    public function __construct(
        private ClassificationRepository $repo
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $classifications = $this->repo->findAll();
        return $this->render('classification/index.html.twig', [
            'classifications' => $classifications,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(?Classification $classification): Response
    {
        return $this->render('classification/show.html.twig', [
            'classification' => $classification,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        //1. créer le formulaire
        $form = $this->createForm(ClassificationForm::class);

        // 2. Écouter les changements dans le formulaire
        $form->handleRequest($request);

        // 3. vérifier si le formulaire est rempli et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $classification = $form->getData();

            // 4. Faire l'enregistrement en BDD
            $em->persist($classification);
            $em->flush();

            return $this->redirectToRoute('app_classification_index');
        }

        // 4. bis Retourner les erreurs ou le formulaire vierge
        return $this->render('classification/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}/edit', name:'edit', methods: ['GET', 'POST'], requirements: ['id' => '\d+'])]
    public function edit(?Classification $classification, Request $request, EntityManagerInterface $em): Response
    {
        if(!$classification) {
            return $this->redirectToRoute('app_classification_index');
        }

        $form = $this->createForm(ClassificationForm::class, $classification);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('app_classification_index');
        }

        return $this->render('classification/edit.html.twig', [
            'form' => $form,
            'classification' => $classification
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function delete(?Classification $classification, EntityManagerInterface $em): Response
    {
        if ($classification) {
            $em->remove($classification);
            $em->flush();
        }

        return $this->redirectToRoute('app_classification_index');
    }
}
