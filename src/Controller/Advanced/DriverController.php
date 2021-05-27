<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Driver;
use App\Form\DriverType;
use App\Repository\DriverRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/driver")
 */
class DriverController extends MainController
{
    /**
     * @Route("/", name="driver_index", methods={"GET"})
     */
    public function index(DriverRepository $driverRepository): Response
    {
        return $this->render(
            'driver/index.html.twig',
            [
                'drivers' => $driverRepository->findBy([], ['psn' => 'asc']),
            ]
        );
    }

    /**
     * @Route("/{id}", name="driver_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Driver $driver): Response
    {
        return $this->render(
            'driver/show.html.twig',
            [
                'driver' => $driver,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="driver_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Driver $driver): Response
    {
        return $this->updateAction($driver, $request, false);
    }

    /**
     * @Route("/{id}", name="driver_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Driver $driver): Response
    {
        return $this->deleteAction($request, $driver);
    }

    /**
     * @Route("/new", name="driver_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Driver(), $request);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'driver';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return DriverType::class;
    }
}
