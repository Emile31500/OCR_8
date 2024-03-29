<?php

namespace Test\Controller;

use RuntimeException;
use App\Repository\UserRepository;
use App\Controller\SecurityController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase{

    public function testLogout() : void
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => 'Emile']);
        $client->loginUser($user);

        $this->assertTrue($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));

        $crawler = $client->request('GET', '/logout');
        $client->followRedirect();
        
        $this->assertFalse($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSame('/', $client->getRequest()->getPathInfo());

    } 

    public function testLoginAuthUser() : void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => 'Emile']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/login');
        $client->followRedirect();

        $this->assertTrue($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSame('/', $client->getRequest()->getPathInfo());

    }

    public function testLoginUnauthUser() : void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        $this->assertFalse($client->getContainer()->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY'));
        $this->assertSame('/login', $client->getRequest()->getPathInfo());

    }

    function unitTestLogout() : void {

        $securityController = $this->createStub(SecurityController::class);
        
        $securityController->expects($this->any())
                    ->method('logoutCheck')
                    ->willReturn(new \Exception);

        $this->assertInstanceOf(RuntimeException::class, $securityController->logoutCheck());
        
    }


}