<?php

namespace App\Controller\api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'api_category_')]
final class CategoryController extends AbstractController{
    #[Route('/categories', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->json($categories, 200, [], ['groups' => ['api_category_index']]);
    }

    #[Route('/category/new', name: 'new', methods: ['POST'])]
    public function new(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json', ['groups' => ['api_category_new']]);

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $messages = [];
            foreach($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json($messages, 400);
        }

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, 201, [], ['groups' => ['api_category_index']]);
    }
}
