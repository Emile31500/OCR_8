<?php 

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class taskTest extends TestCase
{
    public function testSeting()
    {
        $title = "Title";
        $content = "Content";

        $task = new Task();
        $user = new User();

        $this->assertSame($task->seTtitle($title)->getTitle(), $title);
        $this->assertSame($task->setContent($content)->getContent(), $content);
        $this->assertSame($task->setUser($user)->getUser(), $user);

    }

    public function testReturnSeting()
    {
        $title = "Title";
        $content = "Content";

        $task = new Task();
        $user = new User();

        $this->assertSame($task->seTtitle($title), $task);
        $this->assertSame($task->setContent($content), $task);
        $this->assertSame($task->setUser($user), $task);

    }
}