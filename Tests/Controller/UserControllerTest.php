<?php 

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase {

    public function testIndex() {

        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users');
        
        $this->assertResponseIsSuccessful();
    }
    
    public function testCeraetGet(){

        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users/create');
        $this->assertResponseIsSuccessful();

    }

    public function testCreatePost(){

        $client = static::createClient();
        $crawler = $client->request('POST', 'http://127.0.0.1:8000/users/create');
        
        $formData;
        $client->xmlHttpRequest('POST', '/users/create', ['name' => 'Fabien']);
        $this->assertResponseIsSuccessful();
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://127.0.0.1:8000/users/create');

        $client->submitForm('Save', [
            'activateMembership' => 'on',
            'trialPeriod' => '7',
        ]);
        $client->submitForm('Save', [
            'username' => 'User Test',
            'email' => 'user@testphp',
            'email' => 'P@ssw0rd' 

        ]);

        # self::assertFormValue('#form', 'trialPeriod', '7');
        # self::assertCheckboxChecked('activateMembership');
    }

}
