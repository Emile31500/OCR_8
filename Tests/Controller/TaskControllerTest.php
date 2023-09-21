<?php

namespace Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
   public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
    }

    public function testDoneList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks/done');

        $this->assertResponseIsSuccessful();
    }

    public function testCreate(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertSelectorTextContains('h1', 'Hello World');
        
    }

    public function testCreateAuthenticatedClient()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);

        $client->request(
                'GET',
                '/login',
                [],
                [],
                [],
                json_encode([
                    '_username' => $adminUser->getEmail(),
                    '_password' => $adminUser->getPassword(),
                ])
            );
        
        var_dump(json_decode($client->getResponse()->getContent(), true));
        die;
        $data = json_decode($client->getResponse()->getContent(), true);

        
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        //return $client;

    }

    public function testCreateAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);


        $client->loginUser($adminUser);
        //$client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful(); 
        
    }
}
