<?php

namespace Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserRepositoryTest extends WebTestCase {

    
   public function testLoadUserNameUnit() {

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

    public function testLoadUserNameFunctionnal()
    {
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user =$userRepository->loadUserByUsername('Emile');

        $this->assertInstanceOf(User::class, $user);
    }


    public function getUser(string $username)
    {
        $userRepository = $this->objectManager->getRepository(User::class);
        $user = $userRepository->loadUserName($username);

        return $user;
    }
}