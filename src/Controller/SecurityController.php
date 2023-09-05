<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UserPasswordHasherInterface $hash, UserRepository $userRepo)
    {
        
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        
        // $user = $userRepo->loadUserByUsername('Emile2');
        //
        // $verif = $hash->isPasswordValid($user, 'mmm');
        // var_dump($verif);
        // die;
        // 
        // Le mot de passe de Emile2 est bien mmm

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck( ?User $user):Reponse {

    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logoutCheck()
    {
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}