<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/users", name="user_list")
     */
    public function list(UserRepository $userRepository): Response
    {
        if ($this->isGranted("ROLE_ADMIN")){

            $users = $userRepository->findAll();
            return $this->render('user/list.html.twig', ['users' => $users]);
        
        } else {

            return $this->redirect('/');
            
        }
    }

    /**
     * @Route("/users/create", name="user_create")
     */
    public function create(Request $request, UserPasswordHasherInterface  $hasher): Response
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
    public function edit(User $user, Request $request, UserPasswordHasherInterface $hasher): Response
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
  
            return $this->redirect('/');
        }
    }
}
