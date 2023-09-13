<?php 

namespace App\Tests\Entity;

use Datetime;
use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testSeting()
    {
        $task = new Task();
        $user = new User();
        
        $title = "Title";
        $content = "Content";
        $createdAt = new Datetime();

        $this->assertSame($task->seTtitle($title)->getTitle(), $title);
        $this->assertSame($task->setContent($content)->getContent(), $content);
        $this->assertSame($task->setCreatedAt($createdAt)->getCreatedAt(), $createdAt);
        $this->assertSame($task->setUser($user)->getUser(), $user);

    }

    public function testReturnSeting()
    {
        $task = new Task();
        $user = new User();

        $title = "Title";
        $content = "Content";
        $createdAt = new Datetime();

        $this->assertSame($task->seTtitle($title), $task);
        $this->assertSame($task->setContent($content), $task);
        $this->assertSame($task->setCreatedAt($createdAt), $task);
        $this->assertSame($task->setUser($user), $task);

    }
}