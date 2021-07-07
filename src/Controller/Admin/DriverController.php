<?php


namespace App\Controller\Admin;


use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Team;
use App\Repository\DriverRaceRepository;
use App\Repository\DriverRepository;
use App\Repository\TeamRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
     * @Route("/stats/{id}", name="admin_driver_stats", methods={"GET"}, requirements={"id"="\d+"})
     * @param Driver $driver
     * @param DriverRaceRepository $driverRaceRepository
     * @return Response
     */
    public function stats(Driver $driver, DriverRaceRepository $driverRaceRepository): Response
    {
        $driverRaces = $driverRaceRepository->findByDriver($driver);
        $driverRaces = array_filter($driverRaces, static function (DriverRace $driverRace) {
            return $driverRace->getRace()->getDate() instanceof \DateTime;
        });
        usort($driverRaces, static function (DriverRace $driverRaceA, DriverRace $driverRaceB) {
            return $driverRaceA->getRace()->getDate() < $driverRaceB->getRace()->getDate();
        });
        $finishedRaces = [];
        $decoRaces = [];
        $wonRaces = [];
        $missedRaces = [];
        $bestLaps = [];
        foreach ($driverRaces as $driverRace) {
            if ($driverRace->isValid()) {
                $finishedRaces[] = $driverRace;
                if ($driverRace->getFinishPosition() === 0) {
                    $wonRaces[] = $driverRace;
                }
            } elseif ($driverRace->isMissing()) {
                $missedRaces[] = $driverRace;
            } elseif ($driverRace->isDisconnected()) {
                $decoRaces[] = $driverRace;
            }
        }

        foreach ($finishedRaces as $finishedRace) {
            $allInscriptions = $finishedRace->getRace()->getDriverRaces();
            $allInscriptions = array_filter($allInscriptions->toArray(), static function (DriverRace $driverRace) {
                return $driverRace->isValid();
            });
            usort($allInscriptions, static function (DriverRace $a, DriverRace $b) {
                return (int)str_replace(':', '', $a->getBestLap()) > (int)str_replace(':', '', $b->getBestLap());
            });
            if (isset($allInscriptions[0]) && $allInscriptions[0]->getDriver()->getId() === $driver->getId()) {
                $bestLaps[] = $finishedRace;
            }
        }

        return $this->render('admin/driver_stats.html.twig', [
            'decoRaces' => $decoRaces,
            'missedRaces' => $missedRaces,
            'finishedRaces' => $finishedRaces,
            'won' => $wonRaces,
            'all' => $driverRaces,
            'bestLaps' => $bestLaps,
            'driver' => $driver

        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_driver_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $driver = new Driver();
        $driver->setUserGroup($this->getUser()->getUserGroup());
        return $this->updateAction($driver, $request, true, true);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'admin_driver';
    }

    /**
     * @Route("/index",methods={"GET"}, name="driver_admin_index")
     * @param DriverRepository $driverRepository
     * @return Response
     */
    public function index(DriverRepository $driverRepository): Response
    {
        return $this->render(
            'admin/driver_index.html.twig',
            [
                'drivers' => $driverRepository->findBy(['userGroup' => $this->getUser()->getUserGroup()], ['psn' => 'asc']),
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