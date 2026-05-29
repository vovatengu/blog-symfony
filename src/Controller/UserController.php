<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user_index')]
    public function index(PostRepository $postRepository): Response
    {
        /** @var \App\Entity\SonataUserUser $user */
        $user = $this->getUser();

        $posts = $postRepository->findByAuthor($user);

        return $this->render('user/index.html.twig', [
            'posts' => $posts,
        ]);
    }
}
