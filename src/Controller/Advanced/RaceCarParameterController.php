<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\RaceCarParameter;
use App\Form\RaceCarParameterType;
use App\Repository\RaceCarParameterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/race/car/parameter")
 */
class RaceCarParameterController extends MainController
{
    /**
     * @Route("/", name="race_car_parameter_index", methods={"GET"})
     */
    public function index(RaceCarParameterRepository $raceCarParameterRepository): Response
    {
        return $this->render('race_car_parameter/index.html.twig', [
            'race_car_parameters' => $raceCarParameterRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="race_car_parameter_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new RaceCarParameter(), $request);
    }

    /**
     * @Route("/{id}", name="race_car_parameter_show", methods={"GET"})
     */
    public function show(RaceCarParameter $raceCarParameter): Response
    {
        return $this->render('race_car_parameter/show.html.twig', [
            'race_car_parameter' => $raceCarParameter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="race_car_parameter_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RaceCarParameter $raceCarParameter): Response
    {
        return $this->updateAction($raceCarParameter, $request, false);
    }

    /**
     * @Route("/{id}", name="race_car_parameter_delete", methods={"POST"})
     */
    public function delete(Request $request, RaceCarParameter $raceCarParameter): Response
    {
        return $this->deleteAction($request,$raceCarParameter);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_car_parameter';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RaceCarParameterType::class;
    }
}
