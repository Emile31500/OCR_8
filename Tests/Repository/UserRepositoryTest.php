<?php

namespace Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase {

    public function testLoadUserNameFunctionnal()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->loadUserByUsername('Emile');

        $this->assertInstanceOf(User::class, $user);
    }
}