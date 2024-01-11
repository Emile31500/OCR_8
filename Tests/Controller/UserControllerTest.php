<?php 

namespace Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\String\ByteString;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class UserControllerTest extends WebTestCase {
    
    public function testListUnAuth()
    {

        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        
        $client->followRedirect();
        

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }

    public function testListAuthAdmin() {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        
        $client->loginUser($admin);
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        $this->assertResponseIsSuccessful();
    }

    public function testEditUnAuth() {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Emile']);
        $url = 'http://127.0.0.1:8000/users/'.$user->getId().'/edit';
        $crawler = $client->request('GET', $url);
        
        $client->followRedirect();
        

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSame('/', $client->getRequest()->getPathInfo());

    }

    public function testEditAuthAdmin(): void
    {   
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $userTest = $userRepository->findOneBy(['username' => 'User Test']);

        $url = '/users/'.$userTest->getId().'/edit';
        
        $client->loginUser($admin);

        $crawler = $client->request('GET', $url);
        $this->assertResponseIsSuccessful();  
        
        $form = $crawler->selectButton('Modifier')->form();
        
        $random1 = ByteString::fromRandom(8, implode('', range('a', 'z')))->toString();
        $random2 = ByteString::fromRandom(8, implode('', range('a', 'z')))->toString();
        $newEmail = $random1.'@'.$random2.'.com';

        $form['user[username]']->setValue('User Test');
        $form['user[email]']->setValue($newEmail);
        $form['user[password][first]']->setValue('P@ssw0rd');
        $form['user[password][second]']->setValue('P@ssw0rd');
        $client->submit($form);

        $userUpdated = $userRepository->findOneBy(['username' => 'User Test']);

        $this->assertNotEquals($userTest, $userUpdated);
        $this->assertInstanceOf(User::class, $userUpdated);
    }

    public function testCreate(): void
    {   
        
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();  

        $form = $crawler->selectButton('Ajouter')->form();

        $form['user[username]']->setValue('User Test');
        $form['user[email]']->setValue('test@test.test');
        $form['user[password][first]']->setValue('T3$tp@ssw0rd');
        $form['user[password][second]']->setValue('T3$tp@ssw0rd');


        $client->submit($form);
    } 
}
