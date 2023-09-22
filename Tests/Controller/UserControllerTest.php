<?php 

namespace Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {

    public function testIndex() {

        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        
        // dd($client->getRequest()->getPathInfo());

        $this->assertResponseIsSuccessful();
    }

    public function testAuthAdminIndex() {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        
        $client->loginUser($admin);
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        $this->assertResponseIsSuccessful();
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

    public function testCreateWithAuthAdmin(): void
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

        $form['user[username]']->setValue('User Test Updated');
        $form['user[email]']->setValue('test.updated@test.test ');
        $form['user[password][first]']->setValue('p@ssw0rd ùpd@t3d');
        $form['user[password][second]']->setValue('p@ssw0rd ùpd@t3d');
        $client->submit($form);

        $userUpdated = $userRepository->findOneBy(['username' => 'User Test Updated']);

        $this->assertNotEquals($userTest, $userUpdated);
        $this->assertInstanceOf(User::class, $userUpdated);


    }
}
