<?php
namespace App\Repository;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findByIsDone(bool $wantDone): ?array
    {
        return $this->createQueryBuilder('t')
        ->where('t.isDone = :wantDone')
        ->setParameter('wantDone', $wantDone)
        ->getQuery()
        ->getResult();
    }

}