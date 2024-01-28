<?php

namespace Tests\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    /**
     * @group expect200
     * @group list
     * @group unAuth
     */
    public function testList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks');

        $this->assertSelectorNotExists('.alert.alert-danger');
        $this->assertResponseIsSuccessful();
    }

    /**
     * @group expect200
     * @group list
     * @group unAuth
     */
    public function testDoneList(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/tasks/done');

        $this->assertSelectorNotExists('.alert.alert-danger');
        $this->assertResponseIsSuccessful();
    }

    /**
     * @group expectRedirection
     * @group create
     * @group unAuth
     */
    public function testCreateUnAuth(): void
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/tasks/create');

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSame('/', $client->getRequest()->getPathInfo());

    }

    /**
     * @group expect200
     * @group create
     * @group authAdmin
     */
    public function testCreateAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $adminUser = $userRepository->loadUserByUsername('Emile_Admin');

        $client->loginUser($adminUser);

        $crawler = $client->request('GET', '/tasks/create');
        $this->assertResponseIsSuccessful();  

        $form = $crawler->selectButton('Ajouter')->form();

        $form['task[title]']->setValue('Task insert in test');
        $form['task[content]']->setValue('Content');

        $client->submit($form);
    }

    /**
     * @group expectRedirection
     * @group expect200
     * @group edit
     * @group unAuth
     */
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
        $this->assertSame('/login', $client->getRequest()->getPathInfo());
        $this->assertSelectorNotExists('.alert.alert-warning');

    }

    /**
     * @group expect403
     * @group edit
     * @group authUser
     */
    public function testEditTaskDontBelongToAuth(): void
    {
        $client = static::createClient();

        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);

        $user = $userRepository->findOneBy(['username'=>'Emile']);
        $user2 = $userRepository->findOneBy(['username'=>'Emile_Admin']);
        $task = $taksRepository->findOneBy(['user' => $user2->getId()]);

        $client->loginUser($user);

        $id = $task->getId();
        $url = '/tasks/'.$id.'/edit';
        
        $client->request('GET', $url);
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

    }

    /**
     * @group expect200
     * @group expectRedirection
     * @group edit
     * @group authAdmin
    */
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

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
        $this->assertSelectorTextContains('.alert.alert-success', ' La tâche a bien été modifiée.');

    }

    /**
     * @group expectRedirection
     * @group delete
     * @group unAuth
     */
    public function testDeleteUnauth(): void
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $tasks = $taksRepository->findAll();
        $task = $tasks[0];
        $idTask = $task->getId();

        $url = '/tasks/'.$idTask;
        $crawler = $client->request('DELETE', $url);
        $client->followRedirect();

        $this->assertInstanceOf(Task::class, $taksRepository->findOneBy(["id" => $idTask]));
        $this->assertSame('/login', $client->getRequest()->getPathInfo());
        $this->assertSelectorNotExists('.alert.alert-warning');

    }

    /**
     * @group expect403
     * @group delete
     * @group authUser
     */
    public function testDeleteAnoAuthUser(): void
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => 'Emile']);
        $ano = $userRepository->findOneBy(["username" => 'Anonymous']);
        $task = $taksRepository->findOneBy(["user" => $ano->getId()]);
        $idTask = $task->getId();

        $client->loginUser($user);

        $url = '/tasks/'.$idTask;
        $client->request('DELETE', $url);

        $this->assertInstanceOf(Task::class, $taksRepository->findOneBy(["id" => $idTask]));
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

    }

    /**
     * @group expect403
     * @group delete
     * @group authUser
     */
    public function testDeleteTaskDontBelongToAuth(): void
    {
        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(["username" => 'Emile']);
        $user2 = $userRepository->findOneBy(["username" => 'Emile_Admin']);
        $task = $taksRepository->findOneBy(["user" => $user2->getId()]);
        $idTask = $task->getId();

        $client->loginUser($user);

        $url = '/tasks/'.$idTask;
        $client->request('DELETE', $url);

        $this->assertInstanceOf(Task::class, $taksRepository->findOneBy(["id" => $idTask]));
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

    }

    /**
     * @group expect200
     * @group delete
     * @group authUser
     */
    public function testDeleteAuthUser(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Emile']);

        if (isset($user)) {

            $task = $taksRepository->findOneBy(['user' => $user->getId(), 'isDone' => 0]);

            if($task) {

                $id = $task->getId();
                $client->loginUser($user);
                $url = '/tasks/'.$id;
                $crawler = $client->request('DELETE', $url);
                $client->followRedirect();
                $this->assertResponseIsSuccessful();
                $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
                $this->assertSelectorTextContains('.alert.alert-success', ' La tâche a bien été supprimée.');
                $this->assertNull($taksRepository->findOneBy(['id' => $id]));


            }
            
        }
       
    }

    /**
     * @group expect200
     * @group delete
     * @group authAdmin
     */
    public function testDeleteAuthAdmin(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $adminUser = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $task = $taksRepository->findOneBy(['isDone' => 1]);
        $id = $task->getId();


        $client->loginUser($adminUser);

        $url = '/tasks/'.$id;

        $crawler = $client->request('DELETE', $url);

        $this->assertNull($taksRepository->findOneBy(['id' => $id]));

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('/tasks/done', $client->getRequest()->getPathInfo());
        $this->assertSelectorTextContains('.alert.alert-success', ' La tâche a bien été supprimée.');
    }
    
    /**
     * @group expect404
     * @group delete
     * @group authAdmin
     */
    public function testDeleteAuthAdminAndNullUser (): void
    {   
         
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $userAdmin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $ano = $userRepository->findOneBy(['username' => 'Anonymous']);
        $task = $taksRepository->findOneBy(['user' => $ano->getId()]);
 
        if (isset($task)) {

            $id = $task->getId();
            $client->loginUser($userAdmin);
            $url = '/tasks/'.$id;
            $crawler = $client->request('DELETE', $url);
            $this->assertNull($taksRepository->findOneBy(['id' => $id]));

            $client->followRedirect();

            $this->assertResponseIsSuccessful();
            $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
            $this->assertSelectorTextContains('.alert.alert-success', ' La tâche a bien été supprimée.');

        }
         
    }
    
    /**
     * @group expect200
     * @group expectRedirection
     * @group toggle
     * @group authUser
     */
    public function testToggleUndoneTask()
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $untoggledTask = $taksRepository->findOneBy(["isDone" => 0]);

        $status = $untoggledTask->isDone();
        $id = $untoggledTask->getId();
        
        $url = '/tasks/'.$id.'/toggle';
        $crawler = $client->request('GET', $url);
        $toggledTask = $taksRepository->findOneBy(['id' => $id]);


        $this->assertEquals($status, !($toggledTask->isDone()));

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
        $this->assertSelectorTextContains('.alert.alert-success', 'a bien été marquée comme faite.');
        
                        
    }

    /**
     * @group expect200
     * @group expectRedirection
     * @group toggle
     * @group authUser
     */
    public function testToggleDoneTask()
    {

        $client = static::createClient();
        $taksRepository = static::getContainer()->get(TaskRepository::class);
        $untoggledTask = $taksRepository->findOneBy(["isDone" => 1]);

        $status = $untoggledTask->isDone();
        $id = $untoggledTask->getId();
        
        $url = '/tasks/'.$id.'/toggle';
        $crawler = $client->request('GET', $url);
        $toggledTask = $taksRepository->findOneBy(['id' => $id]);


        $this->assertEquals($status, !($toggledTask->isDone()));

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSame('/tasks', $client->getRequest()->getPathInfo());
        $this->assertSelectorTextContains('.alert.alert-success', ' a bien été marquée comme non faite.');
        
                        
    }

}
