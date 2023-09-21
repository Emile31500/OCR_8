<?php

namespace Tests\Controller;

use App\Repository\TaskRepository;
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

   /* public function testCreate(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();

       // $this->assertResponseRedirects('http://127.0.0.1:8000', 200);
       // $this->assertSelectorTextContains('h1', 'Bienvenue sur Todo List, l\'application vous permettant de gérer l\'ensemble de vos tâches sans effort !');
        
    }*/
    
    public function testCreateAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);


        $client->loginUser($adminUser);
        //$client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();  

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]']->setValue('Task insert in test');
        $form['task[content]']->setValue('Content');

        $client->submit($form);
    }

    public function testEditAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $tasks = $taksRepository->findAll();
        $task = $tasks[0];
        $id = $task->getId();


        $client->loginUser($adminUser);

        $url = '/tasks/'.$id.'/edit';

        $crawler = $client->request('GET', $url);
        $this->assertResponseIsSuccessful();  

        $form = $crawler->selectButton('Modifier')->form();

        $newTitle = 'First task updated';
        $newContent = 'Content updated';
        $form['task[title]']->setValue($newTitle);
        $form['task[content]']->setValue($newContent);

        $client->submit($form);

        $taskUpdated = $taksRepository->findOneBy(['id' => $id]);

        $this->assertEquals($newTitle, $taskUpdated->getTitle());
        $this->assertEquals($newContent, $taskUpdated->getContent());
    }

    public function testDeleteAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $tasks = $taksRepository->findAll();
        $task = $tasks[0];
        $id = $task->getId();


        $client->loginUser($adminUser);

        $url = '/tasks/'.$id.'/delete';

        $crawler = $client->request('GET', $url);

        $this->assertNull($taksRepository->findOneBy(['id' => $id]));
    }

    // Excepting works
    public function testDeleteAuthUser(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Emile']);
        $task = $taksRepository->findOneBy(['user' => $user->getId()]);

        $id = $task->getId();
        $client->loginUser($user);
        $url = '/tasks/'.$id.'/delete';
        $crawler = $client->request('GET', $url);
        $this->assertNull($taksRepository->findOneBy(['id' => $id]));
    }

     // Excepting works
     public function testDeleteAuthAdminAndNullUser (): void
     {   
         
         $client = static::createClient();
         $userRepository = static::getContainer()->get(UserRepository::class);
         $taksRepository = static::getContainer()->get(TaskRepository::class);
         $userAdmin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
         $task = $taksRepository->findOneBy(['user' => null]);
 
         $id = $task->getId();
         $client->loginUser($userAdmin);
         $url = '/tasks/'.$id.'/delete';
         $crawler = $client->request('GET', $url);
         $this->assertNull($taksRepository->findOneBy(['id' => $id]));
     }
}
