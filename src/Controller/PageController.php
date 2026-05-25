<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $postRepository): Response
    {
        return $this->render('page/index.html.twig', [
            'posts' => $postRepository->findBy([], ['id' => 'DESC']),
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
}
