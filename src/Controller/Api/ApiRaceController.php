<?php

namespace App\Controller\Api;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Race;
use App\Entity\RaceCarConfiguration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/race")
 */
class ApiRaceController extends AbstractController
{
    /**
     * @Route("/cars", name="api_get_race_cars", methods={"POST"})
     */
    public function getCars(Request $request): JsonResponse
    {
        $output = ['selected' => null, 'list' => []];
        if ($request->request->has('race')) {
            $race = $this->getDoctrine()->getRepository(Race::class)->find($request->request->get('race'));
            if ($race instanceof Race) {
                if ($request->request->has('inscription')) {
                    $inscription = $this->getDoctrine()->getRepository(DriverRace::class)->find(
                        $request->request->get('inscription')
                    );
                    foreach ($race->getCars() as $car) {
                        $output['list'][$car->getMaker()->getName()][$car->getName()] = $car->getId();
                        if (($inscription instanceof DriverRace) && $inscription->getCar(
                            ) instanceof Car && $inscription->getCar()->getId() === $car->getId()) {
                            $output['selected'] = $car->getId();
                        }
                    }
                }
                if ($request->request->has('configuration')) {
                    $configuration = $this->getDoctrine()->getRepository(RaceCarConfiguration::class)->find(
                        $request->request->get('configuration')
                    );
                    foreach ($race->getCars() as $car) {
                        $output['list'][$car->getMaker()->getName()][$car->getName()] = $car->getId();
                        if (($configuration instanceof RaceCarConfiguration) && $configuration->getCar(
                            ) instanceof Car && $configuration->getCar()->getId() === $car->getId()) {
                            $output['selected'] = $car->getId();
                        }
                    }
                }

            }
        }

        return $this->json($output);
    }

    /**
     * @Route("/drivers", name="api_get_race_drivers", methods={"POST"})
     */
    public function getDrivers(Request $request): JsonResponse
    {
        $output = ['selected' => null, 'list' => []];
        $inscription = $this->getDoctrine()->getRepository(DriverRace::class)->find(
            $request->request->get('inscription')
        );

        $allDivers = [];
        foreach ($this->getDoctrine()->getRepository(Driver::class)->findAll() as $driver) {
            $allDivers[$driver->getId()] = $driver;
        }
        $allSubscribedDrivers = [];
        if ($inscription instanceof DriverRace && $inscription->getRace() instanceof Race) {
            foreach ($inscription->getRace()->getDriverRaces() as $subscription) {
                $driver = $subscription->getDriver();
                if ($driver instanceof Driver) {
                    $allSubscribedDrivers[$driver->getId()] = $driver;
                }
            }
            dd($allDivers, $allSubscribedDrivers, array_diff_assoc($allDivers, $allSubscribedDrivers));
        }


        if ($request->request->has('race')) {
            $race = $this->getDoctrine()->getRepository(Race::class)->find($request->request->get('race'));
            if ($race instanceof Race) {
                $inscription = $this->getDoctrine()->getRepository(DriverRace::class)->find(
                    $request->request->get('inscription')
                );
                foreach ($race->getDriverRaces() as $driverRace) {
                    $driver = $driverRace->getDriver();
                    if ($driver instanceof Driver) {
                        $output['list'][$driver->getId()] = $driver->getPsn();
                        if (($inscription instanceof DriverRace) && $inscription->getDriver(
                            ) instanceof Driver && $inscription->getDriver()->getId() === $driver->getId()) {
                            $output['selected'] = $driver->getId();
                        }
                    }
                }

            }
        }

        return $this->json($output);
    }
}
