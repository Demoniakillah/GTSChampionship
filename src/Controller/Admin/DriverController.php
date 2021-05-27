<?php


namespace App\Controller\Admin;


use App\Entity\Driver;
use App\Entity\Team;
use App\Repository\DriverRepository;
use App\Repository\TeamRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Advanced\DriverController as BaseController;

/**
 * DriverController
 * DriverController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 13/05/2021
 * @Route("/admin/driver")
 */
class DriverController extends BaseController
{

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_driver_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Driver(), $request, true, true);
    }

    /**
     * @Route("/index",methods={"GET"}, name="driver_admin_index")
     */
    public function index(DriverRepository $driverRepository): Response
    {
        return $this->render(
            'admin/driver_index.html.twig',
            [
                'drivers' => $driverRepository->findBy([], ['psn' => 'asc']),
                'form_url' => $this->generateUrl('admin_driver_new'),
            ]
        );
    }

    /**
     * @param Request $request
     * @param Driver $driver
     * @return Response
     * @Route("/{id}/edit", name="admin_driver_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Driver $driver): Response
    {
        return $this->updateAction($driver, $request, false, true);
    }
}