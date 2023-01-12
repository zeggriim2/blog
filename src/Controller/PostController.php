<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/posts', name: 'post_')]
final class PostController extends AbstractController
{
    #[Route("", name: "list", methods: [Request::METHOD_GET])]
    public function list(PostRepository $postRepository): Response
    {
        return $this->render('post/list.html.twig', [
            'posts' => $postRepository->findAll()
        ]);
    }

    #[Route("/{id}/read", name: "read", requirements: ['id' => '\d+'], methods: [Request::METHOD_GET])]
    public function read(Post $post): Response
    {
        return $this->render('post/read.html.twig', ['post' => $post]);
    }

    #[Route("/create", name: "create", methods: [Request::METHOD_GET, Request::METHOD_POST])]
    public function create(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class,$post)->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->renderForm('post/create.html.twig', ['form' => $form]);
    }
}