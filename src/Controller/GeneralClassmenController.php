<?php

namespace App\Controller;

use App\Entity\UserGroup;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeneralClassmenController extends AbstractController
{
    /**
     * @Route("/general/classmen/", name="general_classmen")
     */
    public function indexAdmin(): Response
    {
        return $this->render('general_classmen/index.html.twig');
    }

    /**
     * @Route("/public/ranking/{id}", name="ranking")
     * @param UserGroup $userGroup
     * @return Response
     */
    public function indexPublic(UserGroup $userGroup): Response
    {
        return $this->render('general_classmen/public.html.twig',['userGroup' => $userGroup]);
    }
}
