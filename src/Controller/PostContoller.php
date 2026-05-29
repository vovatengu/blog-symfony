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

class PostContoller extends AbstractController
{
    #[Route('post/new', name: 'app_post_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a post');
        }
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setAuthor($user);

            $em->persist($post);
            $em->flush();

            $this->addFlash('success', 'save post');

            return $this->redirectToRoute('app_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/post/{id}/edit', name: 'app_post_edit', requirements: ['id' => '\d+'])]
    public function edit(int $id, Request $request, EntityManagerInterface $em, PostRepository $postRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a post');
        }

        $post = $postRepository->find($id);
        if (!$post) {
            throw $this->createNotFoundException('Post not found');
        }

        if ($post->getAuthor() !== $user) {
            throw $this->createAccessDeniedException('You are not the author of this post');
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('app_post', ['slug' => $post->getSlug()]);
        }

        return $this->render('post/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    #[Route('/post/{id}/delete', name: 'app_post_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, Request $request, EntityManagerInterface $em, PostRepository $postRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException('You must be logged in to create a post');
        }
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
