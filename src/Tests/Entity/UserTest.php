<?php 

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test()
    {
        $username = "Emile";
        $email = "emile00013@gmail.com";
        $password = "P@ssw0rd";
        $roles = ['ROLE_USER'];

        $user = new User();

        $this->assertSame($user->setUsername($username)->getUsername(), $user->getUsername());
        $this->assertSame($user->setEmail($email)->getEmail(), $user->getEmail());
        $this->assertSame($user->setPassword($password)->getPassword(), $user->getPassword());
        $this->assertSame($user->setRoles($roles)->getRoles(), $user->getRoles());

    }
}