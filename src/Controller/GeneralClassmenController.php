<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralClassmenController extends AbstractController
{
    /**
     * @Route("/general/classmen", name="general_classmen")
     */
    public function index(): Response
    {

        return $this->render('general_classmen/index.html.twig', [
            'controller_name' => 'GeneralClassmenController',
        ]);
    }
}
