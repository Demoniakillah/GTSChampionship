<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\RaceCarConfiguration;
use App\Form\RaceCarConfigurationType;
use App\Repository\RaceCarConfigurationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/race/car/configuration")
 */
class RaceCarConfigurationController extends MainController
{
    /**
     * @Route("/", name="race_car_configuration_index", methods={"GET"})
     */
    public function index(RaceCarConfigurationRepository $raceCarConfigurationRepository): Response
    {
        return $this->render('race_car_configuration/index.html.twig', [
            'race_car_configurations' => $raceCarConfigurationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="race_car_configuration_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new RaceCarConfiguration(), $request);
    }

    /**
     * @Route("/{id}", name="race_car_configuration_show", methods={"GET"})
     */
    public function show(RaceCarConfiguration $raceCarConfiguration): Response
    {
        return $this->render('race_car_configuration/show.html.twig', [
            'race_car_configuration' => $raceCarConfiguration,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="race_car_configuration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RaceCarConfiguration $raceCarConfiguration): Response
    {
        return $this->updateAction($raceCarConfiguration, $request, false);
    }

    /**
     * @Route("/{id}", name="race_car_configuration_delete", methods={"POST"})
     */
    public function delete(Request $request, RaceCarConfiguration $raceCarConfiguration): Response
    {
       return $this->deleteAction($request,$raceCarConfiguration);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_car_configuration';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RaceCarConfigurationType::class;
    }
}
