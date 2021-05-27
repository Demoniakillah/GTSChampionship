<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Menu;
use App\Entity\Pool;
use App\Form\DriverRaceFullType;
use App\Form\DriverRaceType;
use App\Repository\DriverRaceRepository;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/driver/race")
 */
class DriverRaceController extends MainController
{
    /**
     * @Route("/", name="driver_race_index", methods={"GET"})
     */
    public function index(DriverRaceRepository $driverRaceRepository): Response
    {
        return $this->render(
            'driver_race/index.html.twig',
            [
                'pools' => $this->getDriverRacesByPool(),
            ]
        );
    }

    /**
     * @Route("/new", name="driver_race_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new DriverRace(), $request);
    }

    /**
     * @Route("/{id}", name="driver_race_show", methods={"GET"})
     */
    public function show(DriverRace $driverRace): Response
    {
        return $this->render(
            'driver_race/show.html.twig',
            [
                'driver_race' => $driverRace,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="driver_race_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DriverRace $driverRace): Response
    {
        return $this->updateAction($driverRace, $request, false);
    }

    /**
     * @Route("/{id}", name="driver_race_delete", methods={"POST"})
     */
    public function delete(Request $request, DriverRace $driverRace): Response
    {
        return $this->deleteAction($request, $driverRace);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'driver_race';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return DriverRaceFullType::class;
    }

    /**
     * @param Request $request
     * @param DriverRace $entity
     * @param FormInterface $form
     */
    protected function submit(Request $request, $entity, FormInterface $form): void
    {
        if($entity->getPool() instanceof Pool && $entity->getDriver() instanceof Driver) {
            $entity->getDriver()->setPool($entity->getPool());
        }
        parent::submit($request, $entity, $form);
    }

    /**
     * @return array
     */
    protected function getDriverRacesByPool(): array
    {
        $output = [];
        foreach ($this->getDoctrine()->getRepository(DriverRace::class)->findAll() as $driverInscription) {
            $pool = $driverInscription->getPool();
            if ($pool instanceof Pool) {
                $output[$pool->getName()][] = $driverInscription;
            }
        }

        return $output;
    }
}
