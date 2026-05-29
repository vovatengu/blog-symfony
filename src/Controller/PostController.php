<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PostController extends AbstractController
{
    #[Route('post/new', name: 'app_post_new')]
    #[Route('/post/{id}/edit', name: 'app_post_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, EntityManagerInterface $em, PostRepository $postRepository, ?int $id = null): Response
    {
        $user = $this->getUser();
        if (null === $id) {
            $post = new Post();
        } else {
            $post = $postRepository->find($id);
            if (!$post) {
                throw $this->createNotFoundException('Post not found');
            }
            if ($post->getAuthor() !== $user) {
                throw $this->createAccessDeniedException('You are not the author of this post');
            }
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($user);

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('app_post', ['slug' => $post->getSlug()]);
        }

        return $this->render(null === $id ? 'post/new.html.twig' : 'post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'app_post_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, PostRepository $postRepository): Response
    {
        $user = $this->getUser();
        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        if ($post->getAuthor() !== $user) {
            throw $this->createAccessDeniedException('You are not the author of this post');
        }

        if (!$this->isCsrfTokenValid('delete-post'.$post->getId(), $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        $em->remove($post);
        $em->flush();

        return $this->redirectToRoute('app_user_index');
    }
}
