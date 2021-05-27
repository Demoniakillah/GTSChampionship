<?php


namespace App\Controller\Admin;


use App\Entity\Driver;
use App\Entity\Terms;
use App\Repository\TermsRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Advanced\TermsController as BaseController;

/**
 * TermsController
 * TermsController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 13/05/2021
 * @Route("/admin/terms")
 */
class TermsController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_terms_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Terms(), $request, true, true);
    }

    /**
     * @param Request $request
     * @param Terms $terms
     * @return Response
     * @Route("/{id}/edit", name="admin_terms_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Terms $terms): Response
    {
        return $this->updateAction($terms, $request, false, true);
    }

    /**
     * @Route("/index",methods={"GET"}, name="terms_admin_index")
     */
    public function index(TermsRepository $termsRepository): Response
    {
        return $this->render('admin/terms_index.html.twig', [
            'terms' => $termsRepository->findBy([],[],1),
            'form_url' => $this->generateUrl('admin_terms_new')
        ]);
    }
}