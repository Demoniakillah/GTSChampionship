<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\RaceParameter;
use App\Form\RaceParameterType;
use App\Repository\RaceParameterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/race/parameter")
 */
class RaceParameterController extends MainController
{
    /**
     * @Route("/", name="race_parameter_index", methods={"GET"})
     */
    public function index(RaceParameterRepository $raceParameterRepository): Response
    {
        return $this->render(
            'race_parameter/index.html.twig',
            [
                'race_parameters' => $raceParameterRepository->findBy([],['name'=>'asc']),
            ]
        );
    }

    /**
     * @Route("/new", name="race_parameter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new RaceParameter(), $request);
    }

    /**
     * @Route("/{id}", name="race_parameter_show", methods={"GET"})
     */
    public function show(RaceParameter $raceParameter): Response
    {
        return $this->render(
            'race_parameter/show.html.twig',
            [
                'race_parameter' => $raceParameter,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="race_parameter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RaceParameter $raceParameter): Response
    {
        return $this->updateAction($raceParameter, $request, false);
    }

    /**
     * @Route("/{id}", name="race_parameter_delete", methods={"POST"})
     */
    public function delete(Request $request, RaceParameter $raceParameter): Response
    {
        return $this->deleteAction($request, $raceParameter);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_parameter';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RaceParameterType::class;
    }
}
