<?php 

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testSeting()
    {
        $username = "Emile";
        $email = "emile00013@gmail.com";
        $password = "P@ssw0rd";
        $roles = ['ROLE_USER'];

        $user = new User();

        $this->assertSame($user->setUsername($username)->getUsername(), $username);
        $this->assertSame($user->setEmail($email)->getEmail(), $email);
        $this->assertSame($user->setPassword($password)->getPassword(), $password);
        $this->assertSame($user->setRoles($roles)->getRoles(), $roles);
    }

    public function testReturnSeting()
    {
        $username = "Emile";
        $email = "emile00013@gmail.com";
        $password = "P@ssw0rd";
        $roles = ['ROLE_USER'];

        $user = new User();
        
        $this->assertSame($user->setUsername($username), $user);
        $this->assertSame($user->setEmail($email), $user);
        $this->assertSame($user->setPassword($password), $user);
        $this->assertSame($user->setRoles($roles), $user);
    }
}