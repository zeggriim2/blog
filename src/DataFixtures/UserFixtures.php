<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

class UserFixtures extends Fixture
{


    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 1 ; $i <= 10; $i++){
            $user = (new User())
                ->setEmail("email$i@email.com")
                ->setPseudo($faker->firstName(['gender' => 'male']))
            ;
            $user->setPassword($this->userPasswordHasher->hashPassword($user, 'password'));

            $manager->persist($user);
        }
        
        $manager->flush();
    }
}
