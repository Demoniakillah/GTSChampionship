<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\RacePoolSaloonLabel;
use App\Form\RacePoolSaloonLabelType;
use App\Repository\RacePoolSaloonLabelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/race/pool/saloon/label")
 */
class RacePoolSaloonLabelController extends MainController
{
    /**
     * @Route("/", name="race_pool_saloon_label_index", methods={"GET"})
     */
    public function index(RacePoolSaloonLabelRepository $racePoolSaloonLabelRepository): Response
    {
        return $this->render('race_pool_saloon_label/index.html.twig', [
            'race_pool_saloon_labels' => $racePoolSaloonLabelRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="race_pool_saloon_label_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new RacePoolSaloonLabel(), $request);
    }

    /**
     * @Route("/{id}", name="race_pool_saloon_label_show", methods={"GET"})
     * @param RacePoolSaloonLabel $racePoolSaloonLabel
     * @return Response
     */
    public function show(RacePoolSaloonLabel $racePoolSaloonLabel): Response
    {
        return $this->render('race_pool_saloon_label/show.html.twig', [
            'race_pool_saloon_label' => $racePoolSaloonLabel,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="race_pool_saloon_label_edit", methods={"GET","POST"})
     * @param Request $request
     * @param RacePoolSaloonLabel $racePoolSaloonLabel
     * @return Response
     */
    public function edit(Request $request, RacePoolSaloonLabel $racePoolSaloonLabel): Response
    {
        return $this->updateAction($racePoolSaloonLabel, $request, false);
    }

    /**
     * @Route("/{id}", name="race_pool_saloon_label_delete", methods={"POST"})
     * @param Request $request
     * @param RacePoolSaloonLabel $racePoolSaloonLabel
     * @return Response
     */
    public function delete(Request $request, RacePoolSaloonLabel $racePoolSaloonLabel): Response
    {
        return $this->deleteAction($request, $racePoolSaloonLabel);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_pool_saloon_label';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RacePoolSaloonLabelType::class;
    }
}
