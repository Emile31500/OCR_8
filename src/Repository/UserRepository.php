<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
    
    public function loadUserByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
        ->where('u.username = :username')
        ->setParameter('username', $username)
        ->getQuery()
        ->getOneOrNullResult();
    }

}
