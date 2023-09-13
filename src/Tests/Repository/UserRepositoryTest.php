<?php

namespace App\Tests\Repository;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;

class UserRepositoryTest extends TestCase {


   public function test() {

        $username = 'Emile';
        $user = new User();
        $user->setUsername($username);
        ;
        $userRepository = $this->createStub(UserRepository::class);
        
        $userRepository->expects($this->any())
                    ->method('loadUserByUsername')
                    ->willReturn($user);

        $this->assertInstanceOf(User::class, $userRepository->loadUserByUsername($username));
    }
}