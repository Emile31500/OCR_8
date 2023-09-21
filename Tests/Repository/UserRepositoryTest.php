<?php

namespace Tests\Repository;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectRepository;

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












        $user = new User();
        $user->setUsername('Emile');

        $userRepository = $this->createMock(ObjectRepository::class);
        $userRepository->expects($this->any())
            ->method('find')
            ->willReturn($user);
            
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($userRepository);

        $userRepositoryTest = new UserRepositoryTest($objectManager);
        $this->assertEquals($user->getUsername(), $userRepositoryTest->getUser('Emile')->getUsername());
    }

    public function getUser(string $username)
    {
        $userRepository = $this->objectManager->getRepository(User::class);
        $user = $userRepository->loadUserName($username);

        return $user;
    }
}