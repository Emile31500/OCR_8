<?php
// src/Controller/ErrorController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/error404", name="error_404")
     */
    public function error404(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'code' => 404,
            'message' => 'The demanded page has not been found.'
        ]);
    }

     /**
     * @Route("/error403", name="error_403")
     */
    public function error403(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error403.html.twig', [
            'code' => 403,
            'message' => 'Access to the demanded page is forbidden.'
        ]);
    }

    /**
     * @Route("/error500", name="error_500")
     */
    public function error500(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig', [
            'code' => 500,
            'message' => 'The server ios facing a problem, we apologize for the inconvenience.'
        ]);
    }

    /**
     * @Route("/error", name="error")
     */
    public function error(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/error.html.twig');
    }
}
