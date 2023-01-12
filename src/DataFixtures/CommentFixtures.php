<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Post> $posts */
        $posts = $manager->getRepository(Post::class)->findAll();

        $faker = Faker\Factory::create('fr_FR');
        foreach ($posts as $post) {
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

    public function getDependencies(): array
    {
        return [
            PostFixtures::class
        ];
    }
}
