<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        /** @var array<array-key, Tag> $tags */
        $tags = $manager->getRepository(Tag::class)->findAll();

        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 100; ++$i) {
            $post = (new Post())
                ->setTitle($faker->words(3,true))
                ->setContent($faker->paragraph())
                ->setImage($faker->imageUrl(400, 300))
            ;

            shuffle($tags);
            foreach (array_slice($tags,0,2) as $tag) {
                $post->getTags()->add($tag);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TagFixtures::class
        ];
    }
}
