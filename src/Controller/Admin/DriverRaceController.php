<?php


namespace App\Controller\Admin;


use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\PoolConfiguration;
use App\Entity\Race;
use App\Entity\Team;
use App\Form\DriverRaceType;
use App\Repository\DriverRaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Advanced\DriverRaceController as BaseController;

/**
 * DriverRaceController
 * DriverRaceController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 13/05/2021
 * @Route("/admin/driver_race")
 */
class DriverRaceController extends BaseController
{
    protected function getFormOptions(): array
    {
        return ['user_group'=>$this->getUser()->getUserGroup()];
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'admin_driver_race';
    }

    protected function getFormTypeClass(): string
    {
        return DriverRaceType::class;
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_driver_race_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new DriverRace(), $request, true, true);
    }

    /**
     * @Route("/index",methods={"GET"}, name="driver_race_admin_index")
     * @param DriverRaceRepository $driverRaceRepository
     * @return Response
     */
    public function index(DriverRaceRepository $driverRaceRepository): Response
    {
        return $this->render(
            'admin/driver_race_index.html.twig',
            [
                'driver_race_inscriptions' => $this->getDriverInscriptionByRaceByPool(),
                'form_url' => $this->generateUrl('admin_driver_race_new'),
                'max_drivers' => (int)$this->getDoctrine()->getRepository(PoolConfiguration::class)->findOneByName('max_drivers')->getValue()
            ]
        );
    }

    /**
     * @param Request $request
     * @param DriverRace $driverRace
     * @return Response
     * @Route("/{id}/edit", name="admin_driver_race_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DriverRace $driverRace): Response
    {
        return $this->updateAction($driverRace, $request, false, true);
    }

    /**
     * @return array
     */
    protected function getDriverInscriptionByRaceByPool(): array
    {
        $races = $this->getDoctrine()->getRepository(Race::class)->findBy(['userGroup'=>$this->getUser()->getUserGroup()]);
        $drivers = $this->getDoctrine()->getRepository(Driver::class)->findBy(['userGroup'=>$this->getUser()->getUserGroup()], ['psn' => 'asc']);
        foreach ($races as $race) {
            $raceInscriptions = $this->getDoctrine()->getRepository(DriverRace::class)->findByRace($race);
            foreach ($drivers as $driver) {
                foreach ($raceInscriptions as $raceInscription) {
                    if ($raceInscription->getDriver() instanceof Driver && $raceInscription->getDriver()->getId() === $driver->getId()) {
                        break;
                    }
                }
            }
        }
        $this->getDoctrine()->getManager()->flush();
        $output = [];
        foreach ($races as $i => $race) {
            $raceInscriptions = $this->getDoctrine()->getRepository(DriverRace::class)->findByRace($race, ['startPosition' => 'asc']);
            $output[$i] = ['race' => $race];
            foreach ($raceInscriptions as $raceInscription) {
                if ($raceInscription->getPool() instanceof Pool) {
                    if (!isset($output[$i]['pools'][$raceInscription->getPool()->getId()])) {
                        $output[$i]['pools'][$raceInscription->getPool()->getId()]['pool'] = $raceInscription->getPool();
                    }
                    $output[$i]['pools'][$raceInscription->getPool()->getId()]['inscriptions'][] = $raceInscription;
                } else {
                    $output[$i]['empty_pool'][] = $raceInscription;
                }
            }
            if (isset($output[$i]['pools']) && is_array($output[$i]['pools'])) {
                uasort($output[$i]['pools'], static function ($a, $b) {
                    return $a['pool']->getPriority() > $b['pool']->getPriority();
                });
            }
        }
        $pools = $this->getDoctrine()->getRepository(Pool::class)->findBy(
            ['userGroup'=>$this->getUser()->getUserGroup()],
            ['priority' => 'asc']
        );
        foreach ($output as $i => $raceInfos) {
            foreach ($pools as $pool) {
                if (!isset($raceInfos['pools'][$pool->getId()])) {
                    $output[$i]['pools'][$pool->getId()] = ['pool' => $pool, 'inscriptions' => []];
                }
            }
        }

        $passed = array_filter($output, static function ($race) {
            return $race['race']->getDate() < new \DateTime();
        });
        uasort($passed, static function ($a, $b) {
            return $a['race']->getDate() < $b['race']->getDate();
        });
        $next = array_filter($output, static function ($race) {
            return $race['race']->getDate() > new \DateTime();
        });
        uasort($next, static function ($a, $b) {
            return $a['race']->getDate() > $b['race']->getDate();
        });

        return [
            'passed' => $passed,
            'next' => $next
        ];
    }
}