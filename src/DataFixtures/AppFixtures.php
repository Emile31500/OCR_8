<?php
namespace App\DataFixtures;

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

    public function load(ObjectManager $manager)
    {

        $emile = new User();
        $emile->setUsername('Emile');
        $emile->setEmail('emile00013@gmail.com');
        $emile->setRoles(['ROLE_USER']);
        $emile->setPassword($this->hasher->hashPassword($emile, 'P@ssw0rd'));
        $manager->persist($emile);


        $emile_admin = new User();
        $emile_admin->setUsername('Emile_Admin');
        $emile_admin->setEmail('emile00013+1@gmail.com');
        $emile_admin->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $emile_admin->setPassword($this->hasher->hashPassword($emile_admin, 'P@ssw0rd'));
        $manager->persist($emile_admin);

        $ano = new User();
        $ano->setUsername('Anonymous');
        $ano->setEmail('ano');
        $ano->setRoles(['ROLE_ANONYMOUS']);
        $ano->setPassword($this->hasher->hashPassword($ano, 'P@ssw0rd'));
        $manager->persist($ano);

        $test_user = new User();
        $test_user->setUsername('User Test');
        $test_user->setEmail('test@test.test');
        $test_user->setRoles(['ROLE_USER']);
        $test_user->setPassword($this->hasher->hashPassword($test_user, 'P@ssw0rd'));
        $manager->persist($test_user);

        for($i = 1; $i < 20; $i++){

            $tasks[$i] = new Task();
            $tasks[$i]->setTitle('Tâche '.$i);

            if ($i < 6 ) {

                $user = $emile;
                $content = 'Tâche indice '.$i.'. Appartient à'.$emile->getUsername();

            } else if ($i < 11) {

                $user = $emile_admin;
                $content = 'Tâche indice '.$i.'. Appartient à'.$emile_admin->getUsername();

            } else if ($i < 16) {

                $user = $ano;
                $content = 'Tâche indice '.$i.'. Appartient à'.$ano->getUsername();

            } else {
                
                $user = $test_user;
                $content = 'Tâche indice '.$i.'. Appartient à'.$test_user->getUsername();
                
            }

            $tasks[$i]->setUser($user);
            $tasks[$i]->setContent($content);

            $tasks[$i]->toggle(($i%2 == 0));
            $manager->persist($tasks[$i]);
        }

        $manager->flush();
    }
}