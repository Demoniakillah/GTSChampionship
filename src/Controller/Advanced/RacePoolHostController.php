<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\RacePoolHost;
use App\Form\RacePoolHostType;
use App\Repository\RacePoolHostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/race/pool/host")
 */
class RacePoolHostController extends MainController
{
    /**
     * @Route("/", name="race_pool_host_index", methods={"GET"})
     * @param RacePoolHostRepository $racePoolHostRepository
     * @return Response
     */
    public function index(RacePoolHostRepository $racePoolHostRepository): Response
    {
        return $this->render('race_pool_host/index.html.twig', [
            'race_pool_hosts' => $racePoolHostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="race_pool_host_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new RacePoolHost(), $request);
    }

    /**
     * @Route("/{id}", name="race_pool_host_show", methods={"GET"})
     */
    public function show(RacePoolHost $racePoolHost): Response
    {
        return $this->render('race_pool_host/show.html.twig', [
            'race_pool_host' => $racePoolHost,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="race_pool_host_edit", methods={"GET","POST"})
     * @param Request $request
     * @param RacePoolHost $racePoolHost
     * @return Response
     */
    public function edit(Request $request, RacePoolHost $racePoolHost): Response
    {
        return $this->updateAction($racePoolHost, $request, false);
    }

    /**
     * @Route("/{id}", name="race_pool_host_delete", methods={"POST"})
     * @param Request $request
     * @param RacePoolHost $racePoolHost
     * @return Response
     */
    public function delete(Request $request, RacePoolHost $racePoolHost): Response
    {
        return $this->deleteAction($request, $racePoolHost);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_pool_host';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RacePoolHostType::class;
    }
}
