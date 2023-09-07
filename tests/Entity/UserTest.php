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
        $password = $this->hasher->hashPassword($user, "P@ssw0rd");
        $roles = ['ROLE_USER'];


        $user = new User();

        $this->assertSame($user->setUsername($username), $user->getUsername());
        $this->assertSame($user->setEmail($email), $user->getEmail());
        $this->assertSame($user->setPassword($password), $user->getPassword());
        $this->assertSame($user->setRoles($roles), $user->getRoles());

    }
}