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
        
        $userTest = new User();
        $userTest->setUsername("User Test");
        $userTest->setEmail("emile00013+2@gmail.com");
        $userTest->setPassword($this->hasher->hashPassword($userTest, "P@ssw0rd"));
        $userTest->setRoles(['ROLE_USER']);

        $anonymousUser = new User();
        $anonymousUser->setUsername("Anonymous");
        $anonymousUser->setEmail('ano@anonymous.ano');
        $anonymousUser->setPassword('');
        $anonymousUser->setRoles(['ROLE_ANONYMOUS']);

        for ($i=0; $i < 10; $i++) { 

            $taskUser[$i] = new Task();
            $taskUser[$i]->setUser($user);
            $taskUser[$i]->setTitle('Tache indice '.$i);
            $taskUser[$i]->setCreatedAt(new DateTime());
            $taskUser[$i]->toggle(false);
            $taskUser[$i]->setContent('Contenu de ma tache ');
            $manager->persist($taskUser[$i]);

        }

        for ($i=0; $i < 10; $i++) { 

            $taskTest[$i] = new Task();
            $taskTest[$i]->setUser($userTest);
            $taskTest[$i]->setTitle('Tache indice '.$i);
            $taskTest[$i]->setCreatedAt(new DateTime());
            $taskTest[$i]->toggle(false);
            $taskTest[$i]->setContent('Contenu de ma tache ');
            $manager->persist($taskTest[$i]);

        }
        
        for ($i=0; $i < 10; $i++) { 

            $taskAdmin[$i] = new Task();
            $taskAdmin[$i]->setUser($admin);
            $taskAdmin[$i]->setTitle('Tache indice '.$i);
            $taskAdmin[$i]->setCreatedAt(new DateTime());
            $taskAdmin[$i]->toggle(false);
            $taskAdmin[$i]->setContent('Contenu de ma tache ');
            $manager->persist($taskAdmin[$i]);


        }

        for ($i=0; $i < 10; $i++) { 

            $taskAnno[$i] = new Task();
            $taskAnno[$i]->setUser($anonymousUser);
            $taskAnno[$i]->setTitle('Tache indice '.$i);
            $taskAnno[$i]->setCreatedAt(new DateTime());
            $taskAnno[$i]->toggle(false);
            $taskAnno[$i]->setContent('Contenu de ma tache ');
            $manager->persist($taskAnno[$i]);

        }

        for ($i=0; $i < 10; $i++) { 

            $taskNullUser[$i] = new Task();
            $taskNullUser[$i]->setTitle('Tache indice '.$i);
            $taskNullUser[$i]->setCreatedAt(new DateTime());
            $taskNullUser[$i]->toggle(false);
            $taskNullUser[$i]->setContent('Contenu de ma tache ');
            $manager->persist($taskNullUser[$i]);

        }


        $manager->persist($user);
        $manager->persist($admin);
        $manager->persist($anonymousUser);
        $manager->persist($userTest);


        $manager->flush();
    }
}
