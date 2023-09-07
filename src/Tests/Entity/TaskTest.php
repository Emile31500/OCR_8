<?php 

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;

use PHPUnit\Framework\TestCase;

class taskTest extends TestCase
{
    public function test()
    {

        $title = "Title";
        $content = "Content";


        $task = new Task();

        $user = $this->getMockBuilder('User')->disableOriginalConstructor()->getMock();
        $user = new User();

        $this->assertSame($task->seTtitle($title)->geTtitle(), $task->geTtitle());
        $this->assertSame($task->setContent($content)->getContent(), $task->getContent());
        $this->assertSame($task->setUser($user)->getUser(), $task->getUser());

    }
}