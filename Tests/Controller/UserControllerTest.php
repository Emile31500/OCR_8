<?php 

namespace Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\String\ByteString;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;



class UserControllerTest extends WebTestCase {

    /**
     * @group expect200
     * @group expectRedirect
     * @group list
     * @group unAuth
     */
    public function testListUnAuth()
    {

        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        
        $client->followRedirect();
        

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains("h1", "Bienvenue sur Todo List, l'application vous permettant de gérer l'ensemble de vos tâches sans effort !");
        $this->assertSame('/', $client->getRequest()->getPathInfo());
    }

    /**
     * @group expect200
     * @group list
     * @group authAdmin
     */
    public function testListAuthAdmin() {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $admin = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        
        $client->loginUser($admin);
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        $this->assertResponseIsSuccessful();
    }

    /**
     * @group expect200
     * @group expectRedirect
     * @group edit
     * @group unAuth
     */
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

    /**
     * @group expect200
     * @group edit
     * @group authAdmin
     */
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

     /**
     * @group expect302
     * @group create
     * @group unAuth
     */
    public function testCreateUnAuth(): void
    {   
       
        $client = static::createClient();

        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client->followRedirect();

        $this->assertSame('/login', $client->getRequest()->getPathInfo());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorExists('#inputEmail');
        $this->assertSelectorExists('#password');


    } 

    /**
     * @group expect403
     * @group create
     * @group authUser
     */
    public function testCreateAuthUser(): void
    {   
        
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneBy(['username' => 'Emile']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users/create');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

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
        $user = $userRepository->findOneBy(['username' => 'Emile_Admin']);
        $client->loginUser($user);

        $crawler = $client->request('GET', '/users/create');


        $form = $crawler->selectButton('Ajouter')->form();

        $userName = 'User created '.ByteString::fromRandom(8, implode('', range('a', 'z')))->toString();
        $random1 = ByteString::fromRandom(8, implode('', range('a', 'z')))->toString();
        $random2 = ByteString::fromRandom(8, implode('', range('a', 'z')))->toString();
        $newEmail = $random1.'@'.$random2.'.com';

        $form['user[username]']->setValue($userName);
        $form['user[email]']->setValue($newEmail);
        $form['user[password][first]']->setValue('T3$tp@ssw0rd');
        $form['user[password][second]']->setValue('T3$tp@ssw0rd');


        $client->submit($form);

        $userCreated = $user = $userRepository->findOneBy(['username' => $userName]);
        $this->assertInstanceOf(User::class, $userCreated);


    } 
}
