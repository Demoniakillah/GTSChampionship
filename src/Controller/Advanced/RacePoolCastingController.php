<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\RacePoolCasting;
use App\Form\RacePoolCastingType;
use App\Repository\RacePoolCastingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/race/pool/casting")
 */
class RacePoolCastingController extends MainController
{
    /**
     * @Route("/", name="race_pool_casting_index", methods={"GET"})
     * @param RacePoolCastingRepository $racePoolCastingRepository
     * @return Response
     */
    public function index(RacePoolCastingRepository $racePoolCastingRepository): Response
    {
        return $this->render('race_pool_casting/index.html.twig', [
            'race_pool_castings' => $racePoolCastingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="race_pool_casting_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new RacePoolCasting(), $request);
    }

    /**
     * @Route("/{id}", name="race_pool_casting_show", methods={"GET"})
     */
    public function show(RacePoolCasting $racePoolCasting): Response
    {
        return $this->render('race_pool_casting/show.html.twig', [
            'race_pool_casting' => $racePoolCasting,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="race_pool_casting_edit", methods={"GET","POST"})
     * @param Request $request
     * @param RacePoolCasting $racePoolCasting
     * @return Response
     */
    public function edit(Request $request, RacePoolCasting $racePoolCasting): Response
    {
        return $this->updateAction($racePoolCasting, $request, false);
    }

    /**
     * @Route("/{id}", name="race_pool_casting_delete", methods={"POST"})
     * @param Request $request
     * @param RacePoolCasting $racePoolCasting
     * @return Response
     */
    public function delete(Request $request, RacePoolCasting $racePoolCasting): Response
    {
        return $this->deleteAction($request, $racePoolCasting);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_pool_casting';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RacePoolCastingType::class;
    }
}
