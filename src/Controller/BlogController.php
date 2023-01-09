<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Form\PostType;
use App\Uploader\UploaderInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_index')]
    public function index(
        ManagerRegistry $managerRegistry,
        Request $request
    ): Response {
        $limit = (int) $request->get('limit', 10);
        $page = (int) $request->get('page', 1);
        $posts = $managerRegistry->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = ceil($posts->count() / $limit);
        $range = range(max($page - 3, 1), min($page + 3, $pages));

        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit,
            'range' => $range,
        ]);
    }

    #[Route('/article-{id}', name: 'blog_read')]
    public function read(
        Post $post,
        ManagerRegistry $managerRegistry,
        Request $request
    ): Response {
        $comment = new Comment();
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $managerRegistry->getManager()->persist($comment);
            $managerRegistry->getManager()->flush();

            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/read.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/publier-article', name: 'blog_create')]
    public function create(
        Request $request,
        ManagerRegistry $managerRegistry,
        UploaderInterface $uploader
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post, ['validation_groups' => ['Default', 'create']])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            $post->setImage($uploader->upload($file));

            $managerRegistry->getManager()->persist($post);
            $managerRegistry->getManager()->flush();

            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/modifier-article/{id}', name: 'blog_update')]
    public function update(
        Post $post,
        Request $request,
        ManagerRegistry $managerRegistry,
        UploaderInterface $uploader
    ): Response {
        $form = $this->createForm(PostType::class, $post)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('file')->getData();

            if (null !== $file) {
                $post->setImage($uploader->upload($file));
            }

            $managerRegistry->getManager()->flush();

            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('blog/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
