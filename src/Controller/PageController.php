<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PaginatorInterface $paginator, PostRepository $postRepository): Response
    {
        $queryBuilder = $postRepository->findAllQueryBuilder();
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('page/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig');
    }

    #[Route('/blog/{slug}', name: 'app_post')]
    public function post(string $slug, PostRepository $postRepository): Response
    {
        $post = $postRepository->findOneBy(['slug' => $slug]);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        return $this->render('page/post.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/category/{slug}', name: 'app_category')]
    public function category(string $slug, EntityManagerInterface $em): Response
    {
        $category = $em->getRepository(Category::class)->findOneBy(['slug' => $slug]);
        if (!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        return $this->render('page/category.html.twig', [
            'category' => $category,
            'posts' => $em->getRepository(Post::class)->findByCategory($category),
        ]);
    }
}
