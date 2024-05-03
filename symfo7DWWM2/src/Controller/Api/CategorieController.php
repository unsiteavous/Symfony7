<?php

namespace App\Controller\Api;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categorie', name: 'api_categorie_')]
class CategorieController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        return $this->json($categories, context: ['groups'=> 'api_categorie_index']);
    }

    #[Route('/new', name:"new", methods:['POST'])]
    public function new(SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $categorie = $serializer->deserialize($request->getContent(),Categorie::class, 'json', ['groups' => 'api_categorie_new'] );

        $errors = $validator->validate($categorie);

        if ($errors->count()) {
            $messages = [];
            foreach ($errors as $error) {
                $messages[] = $error->getMessage();
            }

            return $this->json($messages, Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {
            $em->persist($categorie);
            $em->flush();

            return $this->json('La catégorie a bien été enregistrée', Response::HTTP_CREATED);
        }
    }

    #[Route('/{nom}', name:"show")]
    public function show(Categorie $categorie): JsonResponse
    {
        return $this->json($categorie,Response::HTTP_OK, context: ['groups'=>['api_categorie_index','api_categorie_show']]);
    }

}
