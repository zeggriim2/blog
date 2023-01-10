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
            $post = (new Post())
                ->setTitle("Article N°{$i}")
                ->setContent("Contenu N°{$i}")
                ->setImage('https://via.placeholder.com/400x300')
            ;
            $manager->persist($post);

            for ($j = 1; $j <= rand(5, 15); ++$j) {
                $comment = (new Comment())
                    ->setAuthor("Auteur N°{$i}")
                    ->setContent("Commentaire N°{$j}")
                    ->setPost($post)
                ;
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
