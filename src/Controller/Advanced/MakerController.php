<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Maker;
use App\Form\MakerType;
use App\Repository\MakerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/maker")
 */
class MakerController extends MainController
{
    /**
     * @Route("/", name="maker_index", methods={"GET"})
     */
    public function index(MakerRepository $makerRepository): Response
    {
        return $this->render('maker/index.html.twig', [
            'makers' => $makerRepository->findBy([],['name'=>'asc']),
        ]);
    }

    /**
     * @Route("/new", name="maker_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Maker(), $request);
    }

    /**
     * @Route("/{id}", name="maker_show", methods={"GET"})
     */
    public function show(Maker $maker): Response
    {
        return $this->render('maker/show.html.twig', [
            'maker' => $maker,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="maker_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Maker $maker): Response
    {
        return $this->updateAction($maker, $request, false);
    }

    /**
     * @Route("/{id}", name="maker_delete", methods={"POST"})
     */
    public function delete(Request $request, Maker $maker): Response
    {
        return $this->deleteAction($request,$maker);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'maker';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
       return MakerType::class;
    }
}
