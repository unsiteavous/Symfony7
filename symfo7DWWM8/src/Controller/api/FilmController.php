<?php

namespace App\Controller\api;

use App\Entity\Film;
use App\Repository\CategoryRepository;
use App\Repository\ClassificationRepository;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_film_')]
final class FilmController extends AbstractController{
    #[Route('/films', name: 'index', methods: ['GET'])]
    public function index(FilmRepository $filmRepository): Response
    {
        $films = $filmRepository->findAll();

        return $this->json($films, 200, [], ['groups' => ['api_film_index']]);
    }

    #[Route('/film/new', name: 'new', methods: ['POST'])]
    public function new(
      Request $request,
      SerializerInterface $serializer,
      ValidatorInterface $validator,
      EntityManagerInterface $entityManager,
      CategoryRepository $categoryRepository,
      ClassificationRepository $classificationRepository
      ): Response
    {
        $film = $serializer->deserialize($request->getContent(), Film::class, 'json', ['groups' => ['api_film_new']]);

        $data = json_decode($request->getContent(), true);

        foreach($data->categories as $id) {
          $film->addCategory($categoryRepository->find($id));
        } 

        $film->setClassification($classificationRepository->find($data->classification));

        $errors = $validator->validate($film);

        if (count($errors) > 0) {
            $messages = [];
            foreach($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json($messages, 400);
        }

        $entityManager->persist($film);
        $entityManager->flush();

        return $this->json($film, 201, [], ['groups' => ['api_film_index']]);
    }
}
