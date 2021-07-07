<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\Race;
use App\Entity\Team;
use App\Entity\Terms;
use App\Repository\CarRepository;
use App\Repository\DriverRaceRepository;
use App\Repository\DriverRepository;
use App\Repository\PoolRepository;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RaceInscriptionController extends AbstractController
{
    /**
     * @Route("/public/race/inscription/{id}", name="public_race_inscription", requirements={"id"="\d+"}, methods={"GET"})
     * @param Race $race
     * @param PoolRepository $poolRepository
     * @return Response
     */
    public function index(Race $race, PoolRepository $poolRepository): Response
    {
        if ($race->isValidForInscription()) {
            return $this->render('race_inscription/index.html.twig', [
                'race' => $race,
                //'pools' => $poolRepository->findBy(['userGroup'=>$race->getUserGroup()],['priority' => 'asc'])
            ]);
        }
        if ($race->isPassed()) {
            return $this->render('event_view/index.html.twig', ['race' => $race]);
        }
        return $this->render('race_inscription/not_ready.html.twig', []);
    }

    /**
     * @Route("/public/race/inscription/new", name="new_public_race_inscription", methods={"POST"})
     * @param CarRepository $carRepository
     * @param DriverRepository $driverRepository
     * @param Request $request
     * @return Response
     */
    public function subscribe(DriverRaceRepository $driverRaceRepository, RaceRepository $raceRepository, TeamRepository $teamRepository, CarRepository $carRepository, DriverRepository $driverRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('new_driver_race_inscription', $request->request->get('token'))) {
            throw new \RuntimeException('Internal error');
        }
        $race = $raceRepository->find($request->request->get('race'));
        if ($race instanceof Race) {
            $driver = $driverRepository->findOneBy(['psn' => $request->request->get('psn'), 'userGroup' => $race->getUserGroup()]);
            if ($driver instanceof Driver) {
                $driverRace = $driverRaceRepository->findOneBy(['race' => $race, 'driver' => $driver]);
                $car = $carRepository->find($request->request->get('car'));
                if (!$car instanceof Car) {
                    throw new \RuntimeException('Internal error');
                }
                if ($driverRace instanceof DriverRace) {
                    $driverRace->setCar($car);
                } else {
                    $driverRace = (new DriverRace())->setRace($race)->setCar($car)->setDriver($driver);
                    if ($driver->getPool() instanceof Pool) {
                        $driverRace->setPool($driver->getPool());
                    }
                    $em->persist($driverRace);
                }
                $em->flush();
                return new Response('OK');
            }
            throw new \RuntimeException('Driver not found!');
        }
        throw new \RuntimeException('Internal error');
    }
}
