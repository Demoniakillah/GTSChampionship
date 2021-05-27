<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Menu;
use App\Entity\RaceConfiguration;
use App\Form\RaceConfigurationType;
use App\Repository\MenuRepository;
use App\Repository\RaceConfigurationRepository;
use App\Repository\RaceParameterRepository;
use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/race/configuration")
 */
class RaceConfigurationController extends MainController
{
    /**
     * @Route("/", name="race_configuration_index", methods={"GET"})
     */
    public function index(RaceRepository $raceRepository): Response
    {
        return $this->render(
            'race_configuration/index.html.twig',
            [
                'races' => $raceRepository->findBy([],['name'=>'asc']),
            ]
        );
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race_configuration';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RaceConfigurationType::class;
    }

    /**
     * @Route("/new", name="race_configuration_new", methods={"GET","POST"})
     */
    public function new(Request $request, MenuRepository $menuRepository): Response
    {
        return $this->updateAction(new RaceConfiguration(), $request);

    }

    /**
     * @Route("/{id}", name="race_configuration_show", methods={"GET"})
     */
    public function show(RaceConfiguration $raceConfiguration): Response
    {
        return $this->render(
            'race_configuration/show.html.twig',
            [
                'race_configuration' => $raceConfiguration,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="race_configuration_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, RaceConfiguration $raceConfiguration): Response
    {
        return $this->updateAction($raceConfiguration, $request, false);
    }

    /**
     * @Route("/{id}", name="race_configuration_delete", methods={"POST"})
     */
    public function delete(Request $request, RaceConfiguration $raceConfiguration): Response
    {
        return $this->deleteAction($request, $raceConfiguration);
    }
}
