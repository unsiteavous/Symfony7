<?php

namespace App\Controller\api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/category', name: 'api_category_')]
final class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $repo
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->repo->findAll();
        return $this->json($categories, 200, [], ['groups' => ['api_category_index']]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(?string $slug): JsonResponse
    {
        $category = $this->repo->findOneBy(['slug' => $slug]);
        if (!$category) {
            return $this->json(null, JsonResponse::HTTP_NOT_FOUND);
        }
        return $this->json($category, 200, [], ['groups' => ['api_category_show']]);
    }

    #[Route('/new', name: 'new', methods: ['POST'])]
    public function new(
        Request $request,
        ValidatorInterface $validator,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse
    {
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json', ['groups' => ['api_category_new']]);

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            $messages = [];
            foreach($errors as $error) {
                $messages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json($messages, JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json($category, JsonResponse::HTTP_CREATED, [
            'location' => $urlGenerator->generate('api_category_show', ['slug' => $category->getSlug()]),
            'groups' => ['api_category_show']
        ]);

    }
}
