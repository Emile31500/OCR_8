<?php

namespace Tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    /*
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

    public function testCreateUnAuth(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/tasks/create');

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('/', $client->getRequest()->getPathInfo());
        
    }
*/
    public function testCreateAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        //$adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $adminUser = $userRepository->loadUserByUsername('Emile_Admin');



        $client->loginUser($adminUser);
        //$client = $this->createAuthenticatedClient();

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();  

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]']->setValue('Task insert in test');
        $form['task[content]']->setValue('Content');

        $client->submit($form);
    }
    /*
    public function testEditUnAuth(): void
    {
        $client = static::createClient();

        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $tasks = $taksRepository->findAll();
        $task = $tasks[0];
        $id = $task->getId();
        $url = '/tasks/'.$id.'/edit';
        $crawler = $client->request('GET', $url);
        
        $client->followRedirect();
        
        $this->assertResponseIsSuccessful();
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }

    public function testEditTaskDontBelongToAuth(): void
    {
        $client = static::createClient();

        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['username'=>'Emile']);
        $user2 = $userRepository->findOneBy(['username'=>'Emile_Admin']);
        $task = $taksRepository->findOneBy(['user' => $user2->getId()]);

        $id = $task->getId();
        $url = '/tasks/'.$id.'/edit';
        $crawler = $client->request('GET', $url);
        
        $client->followRedirect();
        
        $this->assertResponseIsSuccessful();
        $this->assertSame('/', $client->getRequest()->getPathInfo());
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

    public function testDeleteUnauth(): void
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $tasks = $taksRepository->findAll();
        $task = $tasks[0];
        $idTask = $task->getId();

        $url = '/tasks/'.$idTask.'/delete';
        $crawler = $client->request('GET', $url);
        $client->followRedirect();

        $this->assertInstanceOf(Task::class, $taksRepository->findOneBy(["id" => $idTask]));
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }

    public function testDeleteAnoAuthUser(): void
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => 'Emile']);
        $task = $taksRepository->findOneBy(["user" => null]);
        $idTask = $task->getId();

        $client->loginUser($user);

        $url = '/tasks/'.$idTask.'/delete';
        $crawler = $client->request('GET', $url);
        $client->followRedirect();

        $this->assertInstanceOf(Task::class, $taksRepository->findOneBy(["id" => $idTask]));
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());

    }

    public function testDeleteTaskDontBelongToAuth(): void
    {
        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => 'Emile']);
        $user2 = $userRepository->findOneBy(["username" => 'User Test']);
        $task = $taksRepository->findOneBy(["user" => $user2->getId()]);
        $idTask = $task->getId();

        $client->loginUser($user);

        $url = '/tasks/'.$idTask.'/delete';
        $crawler = $client->request('GET', $url);
        $client->followRedirect();

        $this->assertInstanceOf(Task::class, $taksRepository->findOneBy(["id" => $idTask]));
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
    }

    public function testDeleteAuthUser(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Emile']);

        if (isset($user)) {

            $task = $taksRepository->findOneBy(['user' => $user->getId()]);

            if($task) {

                $id = $task->getId();
                $client->loginUser($user);
                $url = '/tasks/'.$id.'/delete';
                $crawler = $client->request('GET', $url);
                $this->assertNull($taksRepository->findOneBy(['id' => $id]));

            }
            
        }
       
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

     public function testDeleteAuthAdminAndNullUser (): void
     {   
         
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userAdmin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $task = $taksRepository->findOneBy(['user' => null]);
 
        if (isset($task)) {

            $id = $task->getId();
            $client->loginUser($userAdmin);
            $url = '/tasks/'.$id.'/delete';
            $crawler = $client->request('GET', $url);
            $this->assertNull($taksRepository->findOneBy(['id' => $id]));

        }
         
     }

    public function testToggle()
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $tasks = $taksRepository->findAll();

       
        $untoggledTask = $tasks[0];
        $status = $untoggledTask->isDone();
        $id = $untoggledTask->getId();
        
        $url = '/tasks/'.$id.'/toggle';
        $crawler = $client->request('GET', $url);
        $toggledTask = $taksRepository->findOneBy(['id' => $id]);


        $this->assertEquals($status, !($toggledTask->isDone()));

    }*/
}
