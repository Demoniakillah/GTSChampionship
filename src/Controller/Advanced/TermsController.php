<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Terms;
use App\Form\TermsType;
use App\Repository\TermsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/terms")
 */
class TermsController extends MainController
{
    /**
     * @Route("/", name="terms_index", methods={"GET"})
     */
    public function index(TermsRepository $termsRepository): Response
    {
        return $this->render('terms/index.html.twig', [
            'terms' => $termsRepository->findBy([],[],1),
        ]);
    }

    /**
     * @Route("/new", name="terms_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Terms(), $request);
    }

    /**
     * @Route("/{id}", name="terms_show", methods={"GET"})
     */
    public function show(Terms $term): Response
    {
        return $this->render('terms/show.html.twig', [
            'terms' => $term,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="terms_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Terms $terms): Response
    {
        return $this->updateAction($terms, $request, false);
    }

    /**
     * @Route("/{id}", name="terms_delete", methods={"POST"})
     */
    public function delete(Request $request, Terms $term): Response
    {
        return $this->deleteAction($request, $term);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'terms';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return TermsType::class;
    }
}
