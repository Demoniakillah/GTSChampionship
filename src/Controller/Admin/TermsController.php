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
        $term = new Terms();
        $term->setUserGroup($this->getUser()->getUserGroup());
        return $this->updateAction($term, $request, true, true);
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
     * @param TermsRepository $termsRepository
     * @return Response
     */
    public function index(TermsRepository $termsRepository): Response
    {
        $term = $termsRepository->findBy(['userGroup' => $this->getUser()->getUserGroup()],[],1);
        if(empty($term)){
            $term = new Terms();
            $term->setUserGroup($this->getUser()->getUserGroup());
            $this->getDoctrine()->getManager()->persist($term);
            $this->getDoctrine()->getManager()->flush();
        } else {
            $term = current($term);
        }
        return $this->render('admin/terms_index.html.twig', [
            'terms' => $term,
            'form_url' => $this->generateUrl('admin_terms_new')
        ]);
    }
}