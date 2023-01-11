<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{


    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 10;$i++) {
            $user = (new User())
                ->setEmail(sprintf("email+%d@email.com",$i))
                ->setPseudo(sprintf("pseudo+%d", $i))
            ;
            $user->setPassword($this->userPasswordHasher->hashPassword($user,'password'));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
