<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('usuario')
            ->setPassword('$2y$13$.tl5q.J8qn/o4n69puaQl.BH.SOe8vr4gSH9Z6IWkPtSaoEE0HNje');
        $manager->persist($user);

        $manager->flush();
    }
}
