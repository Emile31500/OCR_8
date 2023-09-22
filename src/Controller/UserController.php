<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function listAction(UserRepository $userRepository)
    {
        if ($this->isGranted("ROLE_ADMIN")){

            $users = $userRepository->findAll();
            return $this->render('user/list.html.twig', ['users' => $users]);
        
        } else {

            header("location: http://127.0.0.1:8000");
            die;
        }
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function createAction(Request $request, UserPasswordHasherInterface  $hasher)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()){

                $em = $this->getDoctrine()->getManager();
                $password = $hasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);
                $user->setRoles(['ROLE_USER']);

                $em->persist($user);
                $em->flush();

                $this->addFlash('success', "L'utilisateur a bien été ajouté.");

                return $this->redirectToRoute('user_list');

            }
        }

        return $this->render('user/create.html.twig', ['form' => $form->createView()]);
    }
    /**
     * @Route("/users/{id}/edit", name="user_edit")
     */
    public function editAction(User $user, Request $request, UserPasswordHasherInterface $hasher)
    {
        if ($this->isGranted("ROLE_ADMIN")){

            $form = $this->createForm(UserType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted()){

                if ($form->isValid()) {

                    $password = $hasher->hashPassword($user, $user->getPassword());
                    $user->setPassword($password);
                    $this->getDoctrine()->getManager()->flush();
    
                    $this->addFlash('success', "L'utilisateur a bien été modifié");
    
                    return $this->redirectToRoute('user_list');
                }
            }
            
            return $this->render('user/edit.html.twig', ['form' => $form->createView(), 'user' => $user]);
        
        } else {

            header("location: http://127.0.0.1:8000");
            die;
        }
    }

    /**
     * @Route("/encodepassword", name="encode_password")
     */
    public function encodePassword(UserPasswordEncoderInterface $encoder, Request $request): JsonResponse
    {
        
        $encodepassword = $encoder->encodePassword($user, $password);

        new JsonResponse(["encoded_password" => $encodepassword]);
    }
}
