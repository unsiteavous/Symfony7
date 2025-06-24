<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Service\SluggerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category', name: 'app_category_')]
final class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $repo
    ) {}

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->repo->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(?string $slug): Response
    {
        $category = $this->repo->findOneBy(['slug' => $slug]);
        if (!$category) {
            return $this->redirectToRoute('app_category_index');
        }
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'], priority: 10)]
    public function new(Request $request, EntityManagerInterface $em, SluggerService $slugger): Response
    {
        $form = $this->createForm(CategoryType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            $category->setSlug($slugger->slug($category->getName(), Category::class));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(?string $slug, Request $request, EntityManagerInterface $em, SluggerService $slugger): Response
    {
        $category = $this->repo->findOneBy(['slug' => $slug]);
        if (!$category) {
            return $this->redirectToRoute('app_category_index');
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $category->setSlug($slugger->slug($category->getName(), Category::class));
            $em->flush();

            return $this->redirectToRoute('app_category_index');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form,
            'category' => $category
        ]);
    }

    #[Route('/{slug}/delete', name: 'delete', methods: ['GET'])]
    public function delete(?string $slug, EntityManagerInterface $em): Response
    {
        $category = $this->repo->findOneBy(['slug' => $slug]);
        if ($category) {
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('app_category_index');
    }
}
