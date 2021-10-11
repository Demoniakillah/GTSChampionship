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
     * @param Request $request
     * @return JsonResponse
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
                        $output['list'][$car->getMaker()->getName()][$car->getName() . ' - ' . $car->getCategory()] = $car->getId();
                        if (($inscription instanceof DriverRace) && $inscription->getCar() instanceof Car && $inscription->getCar()->getId() === $car->getId()) {
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
                        if (($configuration instanceof RaceCarConfiguration) && $configuration->getCar() instanceof Car && $configuration->getCar()->getId() === $car->getId()) {
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
     * @param Request $request
     * @return JsonResponse
     */
    public function getDrivers(Request $request): JsonResponse
    {

        $output = ['selected' => null, 'list' => []];
        if ($request->request->has('race')) {
            $race = $this->getDoctrine()->getRepository(Race::class)->find($request->request->get('race'));
            $raceInscriptions = $this->getDoctrine()->getRepository(DriverRace::class)->findBy(['race' => $race]);
            $drivers = $this->getDoctrine()->getRepository(Driver::class)->findBy(['userGroup'=>$this->getUser()->getUserGroup()]);
            $driverLight = [];
            foreach ($drivers as $driver) {
                $driverLight[$driver->getId()] = $driver->getPsn();
            }
            foreach ($raceInscriptions as $raceInscription) {
                if ($raceInscription->getDriver() instanceof Driver && isset($driverLight[$raceInscription->getDriver()->getId()])) {
                    unset($driverLight[$raceInscription->getDriver()->getId()]);
                }
            }
            $output['list'] = $driverLight;
        }
        $output['list'] = array_flip($output['list']);
        ksort($output['list'], SORT_FLAG_CASE|SORT_ASC);
        $selectedInscription =  $this->getDoctrine()->getRepository(DriverRace::class)->find($request->request->get('inscription'));

        if($selectedInscription instanceof DriverRace){
            $output['selected']['id'] = $selectedInscription->getDriver()->getId() ;
            $output['selected']['psn'] = $selectedInscription->getDriver()->getPsn() ;
        }
        return $this->json($output);
    }
}
