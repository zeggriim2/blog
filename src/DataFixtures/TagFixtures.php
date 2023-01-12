<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1; $i <= 5 ;$i++) {
            $tag = (new Tag())
                ->setName($faker->word())
            ;
            $manager->persist($tag);
        }

        $manager->flush();
    }
}
