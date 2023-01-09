<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 100; ++$i) {
            $post = new Post();
            $post->setTitle("Article N°{$i}");
            $post->setContent("Contenu N°{$i}");
            $post->setImage('https://via.placeholder.com/400x300');
            $manager->persist($post);

            for ($j = 1; $j <= rand(5, 15); ++$j) {
                $comment = new Comment();
                $comment->setAuthor("Auteur N°{$i}");
                $comment->setContent("Commentaire N°{$j}");
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
