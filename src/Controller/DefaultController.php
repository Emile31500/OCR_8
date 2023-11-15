<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(): Response
    {

        $isAdmin = $this->isGranted("ROLE_ADMIN");
        return $this->render('default/index.html.twig', ['isAdmin' => $isAdmin]);
    }
}
