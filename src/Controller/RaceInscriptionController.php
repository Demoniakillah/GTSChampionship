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
use App\Repository\PoolConfigurationRepository;
use App\Repository\PoolParameterRepository;
use App\Repository\PoolRepository;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RaceInscriptionController extends AbstractController
{


    /**
     * @Route("/public/race/places/left/{id}", name="public_race_inscription_places_left", requirements={"id"="\d+"}, methods={"GET"})
     * @param Race $race
     * @param PoolConfigurationRepository $poolConfigurationRepository
     * @param PoolRepository $poolRepository
     * @return JsonResponse
     */
    public function leftPlaces(Race $race, PoolConfigurationRepository  $poolConfigurationRepository, PoolRepository $poolRepository): JsonResponse
    {
        $pools = $poolRepository->findBy(['userGroup' => $race->getUserGroup()]);
        $nbPools = count($pools);
        $maxByPool = (int)$poolConfigurationRepository->findOneBy(['name'=>'max_drivers'])->getValue();
        $nbPlacesTotal = $maxByPool * $nbPools;
        foreach ($race->getDriverRaces() as $driverRace){
            if($driverRace->hasBeenValidated()){
                $nbPlacesTotal--;
            }
        }
        return $this->json($nbPlacesTotal);
    }

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
            ]);
        }
        if ($race->isPassed()) {
            return $this->render('event_view/index.html.twig', ['race' => $race]);
        }
        return $this->render('race_inscription/not_ready.html.twig', []);
    }

    /**
     * @param string $token
     * @return Response
     * @Route("/public/race/inscription/validation/{token}", name="new_public_race_inscription_validation", methods={"GET"})
     */
    public function confirmEmail(string $token, DriverRaceRepository $driverRaceRepository):Response
    {
        $driverRace = $driverRaceRepository->findOneBy(['validationToken'=>$token]);
        if($driverRace instanceof DriverRace && !$driverRace->hasBeenValidated() && !$driverRace->getRace()->isPassed()){
            $driverRace->validateInscription();
            $this->getDoctrine()->getmanager()->flush();
            return $this->render('race_inscription/confirm_success.html.twig',['race'=>$driverRace->getRace(), 'user'=>$driverRace->getDriver()]);
        }
        return $this->render('race_inscription/not_found.html.twig');
    }

    /**
     * @Route("/public/race/inscription/new", name="new_public_race_inscription", methods={"POST"})
     * @param DriverRaceRepository $driverRaceRepository
     * @param RaceRepository $raceRepository
     * @param TeamRepository $teamRepository
     * @param CarRepository $carRepository
     * @param DriverRepository $driverRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function subscribe(DriverRaceRepository $driverRaceRepository, RaceRepository $raceRepository, TeamRepository $teamRepository, CarRepository $carRepository, DriverRepository $driverRepository, Request $request, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('new_driver_race_inscription', $request->request->get('token'))) {
            throw new \RuntimeException('Internal error');
        }
        $validationToken = sha1(uniqid('driver_race', true) . microtime());
        $email = trim($request->request->get('email'));
        if($email !== '' && preg_match('/^.+@.+\..+$/', $email)){
            $race = $raceRepository->find($request->request->get('race'));
            if ($race instanceof Race) {
                $psn = trim($request->request->get('psn'));
                if($psn !== '' && preg_match('/^\w/', $psn)){
                    $this->sendVerificationEmail($email, $psn, $race, $validationToken);
                    $driver = $driverRepository->findOneBy(['psn' => $psn, 'userGroup' => $race->getUserGroup()]);
                    if (!$driver instanceof Driver) {
                        $driver = new Driver();
                        $driver->setPsn(trim($request->request->get('psn')))->setUserGroup($race->getUserGroup());
                        $em->persist($driver);
                        $em->flush();
                    }
                    if ($request->request->has('team') && strlen($request->request->get('team')) > 1) {
                        $team = $teamRepository->findOneBy(['name' => trim($request->request->get('team')), 'userGroup' => $race->getUserGroup()]);
                        if (!$team instanceof Team) {
                            $team = (new Team())->setName(trim($request->request->get('team')))->setUserGroup($race->getUserGroup());
                            $em->persist($team);
                            $em->flush();
                        }
                        $driver->setTeam($team);
                    }
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
                    $driverRace->setValidationToken($validationToken);
                    $em->flush();
                    return new Response('OK');
                }
            }
        }
        throw new \RuntimeException('Internal error');
    }

    /**
     * @param string $email
     * @param string $psn
     * @param Race $race
     */
    protected function sendVerificationEmail(string $email, string $psn, Race $race, string $validationToken): void
    {
        $to = $email;
        $subject = "email confirmation";
        $message = $this->renderView(
            'race_inscription/email_confirm.html.twig',
            [
                'user' => $psn,
                'link' => $this->generateUrl('new_public_race_inscription_validation', ['token'=>$validationToken], UrlGeneratorInterface::ABSOLUTE_URL),
                'race' => $race,
            ]
        );
        $headers = [
            'From: admin@gtsportchamp.leda-concept.com',
            'Reply-To: admin@gtsportchamp.leda-concept.com',
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=iso-8859-1',
            'X-Mailer: PHP/' . PHP_VERSION,
        ];
        mail($to, $subject, $message, implode("\r\n", $headers));
    }
}
