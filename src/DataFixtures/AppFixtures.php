<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;

    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("Emile");
        $user->setEmail("emile00013@gmail.com");
        $user->setPassword($this->hasher->hashPassword($user, "P@ssw0rd"));
        $user->setRoles(['ROLE_USER']);
        
        $admin = new User();
        $admin->setUsername("Emile_Admin");
        $admin->setEmail("emile00013+1@gmail.com");
        $admin->setPassword($this->hasher->hashPassword($admin, "P@ssw0rd"));
        $admin->setRoles(['ROLE_USER','ROLE_ADMIN']);

        $anonymousUser = new User();
        $anonymousUser->setUsername("Anonymous");
        $anonymousUser->setEmail('ano@anonymous.ano');
        $anonymousUser->setPassword('');
        $anonymousUser->setRoles(['ROLE_ANONYMOUS']);

        $task0 = new Task();
        $task0->setUser($user);
        $task0->setTitle('Tache n° 1');
        $task0->setCreatedAt(new DateTime());
        $task0->toggle(false);
        $task0->setContent('Contenu de ma tache 1');

        $task1 = new Task();
        $task1->setUser($admin);
        $task1->setTitle('Tache n° 2');
        $task1->setCreatedAt(new DateTime());
        $task1->toggle(false);
        $task1->setContent('Contenu de ma tache 2');

        $task2 = new Task();
        $task2->setUser($anonymousUser);
        $task2->setTitle('Tache n° 3');
        $task2->setCreatedAt(new DateTime());
        $task2->toggle(false);
        $task2->setContent('Contenu de ma tache 3');


        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($anonymousUser);

        $manager->persist($task0);
        $manager->persist($task1);
        $manager->persist($task2);

        $manager->flush();
    }
}
