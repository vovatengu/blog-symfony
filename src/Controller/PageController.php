<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PostRepository $postRepository): Response
    {
        $sessionCounter = (int) $request->getSession()->get('session_counter', 0) + 1;
        $request->getSession()->set('session_counter', $sessionCounter);

        $cookieCounter = (int) $request->cookies->get('cookie_counter', 0) + 1;

        $response = $this->render('page/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);

        $response->headers->setCookie(
            Cookie::create('cookie_counter', (string) $cookieCounter, time() + (365 * 24 * 60 * 60))
        );

        return $response;
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
