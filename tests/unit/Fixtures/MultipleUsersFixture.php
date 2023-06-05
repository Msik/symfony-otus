<?php

namespace UnitTests\Fixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MultipleUsersFixture extends Fixture
{
    const DAFAULT_USER_PHONE = '79998887766';

    public function load(ObjectManager $manager): void
    {
        $this->makeUser($manager, self::DAFAULT_USER_PHONE);

        $manager->flush();
    }

    private function makeUser(ObjectManager $manager, string $phone): User
    {
        $user = new User();
        $user->setPhone($phone);
        $manager->persist($user);

        return $user;
    }
}
