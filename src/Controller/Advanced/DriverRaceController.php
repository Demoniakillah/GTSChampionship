<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Menu;
use App\Entity\Pool;
use App\Entity\Race;
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
     * @param DriverRaceRepository $driverRaceRepository
     * @return Response
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
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new DriverRace, $request);
    }

    /**
     * @Route("/{id}", name="driver_race_show", methods={"GET"})
     * @param DriverRace $driverRace
     * @return Response
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
     * @param Request $request
     * @param DriverRace $driverRace
     * @return Response
     */
    public function edit(Request $request, DriverRace $driverRace): Response
    {
        return $this->updateAction($driverRace, $request, false);
    }

    /**
     * @Route("/{id}/remove", name="driver_race_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param DriverRace $driverRace
     * @return Response
     */
    public function delete(Request $request, DriverRace $driverRace): Response
    {
        return $this->deleteAction($request, $driverRace);
    }

    /**
     * @Route("/remove/multi", name="driver_race_delete_multi", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function deleteMulti(Request $request): Response
    {
        foreach ($request->request->get('remove_list') as $toRemove){
            $this->deleteAction(
                $request,
                $this->getDoctrine()->getRepository(DriverRace::class)->find($toRemove['id']),
                $toRemove['token']
            );
        }
        return $this->json(true);
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
        $entity->validateInscription();
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
