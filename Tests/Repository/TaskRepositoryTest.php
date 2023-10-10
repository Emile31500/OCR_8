<?php

namespace Tests\Repository;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskRepositoryTest extends WebTestCase {

    public function testFindByIsDone()
    {
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findByIsDone(false);

        $this->assertInstanceOf(Task::class, $task[0]);
    }
}