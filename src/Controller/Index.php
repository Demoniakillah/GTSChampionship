<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Index
 * Index.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 07/05/2021
 */
class Index extends AbstractController
{
    /**
     * @Route("/", name="admin_welcome", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', []);
    }
}