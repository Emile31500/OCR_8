<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $isAdmin = $this->isGranted("ROLE_ADMIN");
        return $this->render('default/index.html.twig', ['isAdmin' => $isAdmin]);
    }
}
