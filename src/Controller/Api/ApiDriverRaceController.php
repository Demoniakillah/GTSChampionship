<?php

namespace App\Controller\Api;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\PoolConfiguration;
use App\Entity\Race;
use App\Repository\CarRepository;
use App\Repository\DriverRaceRepository;
use App\Repository\DriverRepository;
use App\Repository\PoolRepository;
use App\Repository\RaceRepository;
use App\Tools;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/driver_race")
 */
class ApiDriverRaceController extends AbstractController
{


    /**
     * @Route("/quick/manage/data/update/driver/car", name="api_update_driver_car_quick_manage", methods={"POST"})
     * @param Request $request
     * @param DriverRaceRepository $driverRaceRepository
     * @param CarRepository $carRepository
     * @return JsonResponse
     */
    public function updateDriverRaceCar(Request  $request, DriverRaceRepository $driverRaceRepository, CarRepository $carRepository):JsonResponse
    {
        if($request->request->has('id') && $request->request->has('car') && $request->request->get('id') !== '' && $request->request->get('car') !== ''){
            $inscription = $driverRaceRepository->find($request->request->get('id'));
            if($inscription instanceof DriverRace){
                $car = $carRepository->find($request->request->get('car'));
                if($car instanceof Car){
                    $inscription->setCar($car);
                    $this->getDoctrine()->getManager()->flush();
                    return $this->json(true);
                }
            }
        }
        throw new \RuntimeException("Internal error");
    }

    /**
     * @Route("/random/start/grill", name="api_random_start_grill")
     * @param Request $request
     * @param DriverRaceRepository $driverRaceRepository
     * @return JsonResponse
     */
    public function randomStartGrill(Request  $request, DriverRaceRepository $driverRaceRepository):JsonResponse
    {
        $poolId = $request->request->get('pool');
        $raceId = $request->request->get('race');
        $inscriptions = $driverRaceRepository->findBy(['race'=>$raceId, 'pool'=>$poolId]);
        $nb = count($inscriptions);
        $arrayPositions = [];
        if($nb>2){
            for ($position = 0; $position<$nb; $position++){
                $arrayPositions[] = $position;
            }
            foreach ($inscriptions as $inscription){
                $startPosition = array_rand($arrayPositions);
                $inscription->setStartPosition($startPosition);
                unset($arrayPositions[$startPosition]);
            }
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->json('ok');
    }

    /**
     * @Route("/quick/manage/data/remove", name="api_delete_driver_race_quick_data", methods={"POST"})
     * @param Request $request
     * @param DriverRaceRepository $driverRaceRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function remove(Request $request, DriverRaceRepository $driverRaceRepository, EntityManagerInterface $em): Response
    {
        if($request->request->get('id')) {
            $inscription = $driverRaceRepository->find($request->request->get('id'));
            if ($inscription instanceof DriverRace) {
                $em->remove($inscription);
                $em->flush();
            }
        }
        return new Response('ok');
    }

    /**
     * @Route("/quick/manage/data/", name="api_set_driver_race_quick_data", methods={"POST"})
     * @param Request $request
     * @param CarRepository $carRepository
     * @param PoolRepository $poolRepository
     * @param DriverRepository $driverRepository
     * @param DriverRaceRepository $driverRaceRepository
     * @param RaceRepository $raceRepository
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function setQuickRaceInscription(
        Request $request,
        CarRepository $carRepository,
        PoolRepository $poolRepository,
        DriverRepository $driverRepository,
        DriverRaceRepository $driverRaceRepository,
        RaceRepository $raceRepository,
        EntityManagerInterface $em
    ): Response
    {
        $inscription = null;
        if($request->request->get('id')) {
            $inscription = $driverRaceRepository->find($request->request->get('id'));
        }
        $race = $raceRepository->find($request->request->get('race'));
        $driver = $driverRepository->find($request->request->get('driver'));
        $inscription = $driverRaceRepository->findOneBy([
            'race' => $race,
            'driver' => $driver
        ]);
        if (!$inscription instanceof DriverRace) {
            $inscription = new DriverRace();
            $inscription
                ->validateInscription()
                ->setCar($carRepository->find($request->request->get('car')))
                ->setRace($race)
                ->setStartPosition($request->request->get('position'))
                ->setPool($poolRepository->find($request->request->get('pool')))
                ->setDriver($driver);
            $em->persist($inscription);

            foreach ($inscription->getRace()->getDriverRaces() as $driverRace) {
                $driverRace->setStartPosition(array_search((int)$driverRace->getDriver()->getId(), array_map('intval', $request->request->get('start_positions')), true));
            }
            $em->flush();
        }
        return new Response($inscription->getId());
    }

    /**
     * @Route("/quick/manage/data/{id}", name="api_get_driver_race_quick_data", methods={"GET"}, requirements={"id"="\d+"})
     * @param Race $race
     * @return Response
     */
    public function getQuickRaceInscriptionManagementData(Race $race): Response
    {
        $driversIndexed = [];
        $drivers = $this->getDoctrine()->getRepository(Driver::class)->findBy(['userGroup' => $this->getUser()->getUserGroup()], ['psn' => 'asc']);
        foreach ($drivers as $driver) {
            $driversIndexed[$driver->getId()] = $driver;
        }
        foreach ($race->getDriverRaces() as $inscription) {
            unset($driversIndexed[$inscription->getDriver()->getId()]);
        }
        $poolDrivers = [];
        foreach ($this->getDoctrine()->getRepository(Pool::class)->findBy(['userGroup' => $this->getUser()->getUserGroup()], ['priority' => 'asc']) as $pool) {
            $poolDrivers[$pool->getName()] = ['pool' => $pool, 'drivers' => []];
        }
        foreach ($race->getDriverRaces() as $inscription) {
            if ($inscription->getPool() instanceof Pool) {
                $poolDrivers[$inscription->getPool()->getName()]['drivers'][] = $inscription;
            }
        }

        return $this->render('admin/quick_manage_race_inscriptions.html.twig', [
            'unsubscribed_drivers' => $driversIndexed,
            'pool_drivers' => $poolDrivers,
            'race' => $race
        ]);
    }

    /**
     * @Route("/finish/positions", name="api_update_driver_race_finish_positions", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDriversFinishPosition(Request $request): JsonResponse
    {
        $driverRaces = [];
        foreach ($request->request->get('data') as $data) {
            $driverRace = $this->getDoctrine()->getRepository(DriverRace::class)->find($data['id']);
            if ($driverRace instanceof DriverRace) {
                $driverRace
                    ->setFinishPosition($data['data']['finishPosition'])
                    ->setTotalTime($data['data']['totalTime'] === '' ? '00:00:00.000' : $data['data']['totalTime'])
                    ->setBestLap($data['data']['bestLap'] === '' ? '00:00:00.000' : $data['data']['bestLap'])
                    ->setFinishStatus((int)$data['data']['finishStatus'])
                    ->setBonus((int)$data['data']['bonus'])
                    ->setPenalty((int)$data['data']['penalty']);
                $pool = $this->getDoctrine()->getRepository(Pool::class)->find($data['data']['pool']);
                if ($pool instanceof Pool) {
                    $driverRace->setPool($pool);
                    $driverRace->getDriver()->setPool($pool);
                }
                $car = $this->getDoctrine()->getRepository(Car::class)->find($data['data']['car']);
                if ($car instanceof Car) {
                    $driverRace->setCar($car);
                }
            }
            $driverRaces[] = $driverRace;
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }

    /**
     * @Route("/positions", name="api_update_driver_race_positions", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDriversPosition(Request $request): JsonResponse
    {
        $maxDriversByPool = $this->getDoctrine()->getRepository(PoolConfiguration::class)->findOneByName('max_drivers');
        foreach ($request->request->get('pools') as $requestPool) {
            $i = 0;
            $pool = $requestPool['id'] === '' ? null : $this->getDoctrine()->getRepository(Pool::class)->find(
                $requestPool['id']
            );
            if (isset($requestPool['inscriptions'])) {
                foreach ($requestPool['inscriptions'] as $inscription) {
                    $inscriptionEntity = $this->getDoctrine()->getRepository(DriverRace::class)->find(
                        $inscription['inscription']
                    );
                    if ($i >= (int)$maxDriversByPool->getValue()) {
                        $pool = null;
                    }
                    if ($inscriptionEntity instanceof DriverRace) {
                        $inscriptionEntity->setStartPosition((int)$inscription['position']);
                        if ($pool instanceof Pool) {
                            $inscriptionEntity->setPool($pool);
                            if($inscriptionEntity->getDriver() instanceof Driver) {
                                $inscriptionEntity->getDriver()->setPool($pool);
                            }
                        }
                        $i++;
                    }
                }
            }
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }
}
