<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 100; ++$i) {
            $post = new Post();
            $post->setTitle($faker->sentence());
            $post->setContent($faker->paragraph());
            $post->setImage($faker->imageUrl(400, 300));
            $manager->persist($post);

            for ($j = 1; $j <= rand(5, 15); ++$j) {
                $comment = new Comment();
                $comment->setAuthor($faker->name());
                $comment->setContent($faker->sentence(5));
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
