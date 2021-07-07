<?php


namespace App\Controller\Admin;


use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\Race;
use App\Entity\RaceCarConfiguration;
use App\Entity\RaceConfiguration;
use App\Entity\Team;
use App\Form\RaceResultsType;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Advanced\RaceController as BaseController;

/**
 * RaceController
 * RaceController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 13/05/2021
 * @Route("/admin/race")
 */
class RaceController extends BaseController
{
    /**
     * @Route("/results/{race}/{pool}", name="admin_race_results", methods={"GET"}, requirements={"pool"="\d+","race"="\d+"})
     * @param RaceRepository $raceRepository
     * @param int $race
     * @param int|null $pool
     * @return Response
     */
    public function results(RaceRepository $raceRepository, int $race, int $pool = null): Response
    {
        $raceEntity = $raceRepository->find($race);
        $form = $this->createForm(
            RaceResultsType::class,
            $raceEntity,
            ['pool_id' => $pool]
        );

        return $this->render(
            'admin/race_results.html.twig',
            [
                'race' => $raceEntity,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_race_show", methods={"GET"}, requirements={"id"="\d+"})
     * @param Race $race
     * @return Response
     */
    public function show(Race $race): Response
    {
        return $this->render(
            'admin/race_show.html.twig',
            $this->getRaceViewParams($race)
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_race_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $race = new Race();
        $race->setUserGroup($this->getUser()->getUserGroup());
        return $this->updateAction($race, $request, true, true);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'admin_race';
    }

    /**
     * @Route("/index",methods={"GET"}, name="race_admin_index")
     * @param RaceRepository $raceRepository
     * @return Response
     */
    public function index(RaceRepository $raceRepository): Response
    {
        $races = $raceRepository->findBy(['userGroup'=>$this->getUser()->getUserGroup()], ['name'=>'asc','date' => 'desc']);
        $racesSorted = ['next' => [], 'passed' => []];
        $now = new \DateTime();
        foreach ($races as $race) {
            if ($race->getDate() > $now) {
                $racesSorted['next'][] = $race;
            } else {
                $racesSorted['passed'][] = $race;
            }
        }

        uasort(
            $racesSorted['next'],
            static function ($a, $b) {
                return $a->getDate() > $b->getDate();
            }
        );
        uasort(
            $racesSorted['passed'],
            static function ($a, $b) {
                return $a->getDate() < $b->getDate();
            }
        );


        return $this->render(
            'admin/race_index.html.twig',
            [
                'races' => $racesSorted,
                'form_url' => $this->generateUrl('admin_race_new'),
            ]
        );
    }

    /**
     * @param Request $request
     * @param Race $race
     * @return Response
     * @Route("/{id}/edit", name="admin_race_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Race $race): Response
    {
        return $this->updateAction($race, $request, false, true);
    }

    /**
     * @param Request $request
     * @param Race $race
     * @return Response
     * @Route("/{id}/copy", name="admin_race_duplicate", methods={"GET","POST"})
     */
    public function duplicate(Request $request, Race $race): Response
    {
        $duplicatedRace = new Race($race);
        $duplicatedRace->setName( "New race - " . time());
        $duplicatedRace->setUserGroup($race->getUserGroup());
        $this->getDoctrine()->getManager()->persist($duplicatedRace);
        $carConfigurations = $this->getDoctrine()->getRepository(RaceCarConfiguration::class)->findBy(['race'=>$race]);
        foreach ($carConfigurations as $carConfiguration){
            $duplicatedCarConfiguration = new RaceCarConfiguration();
            $duplicatedCarConfiguration->setRace($duplicatedRace);
            $duplicatedCarConfiguration->setParameter($carConfiguration->getParameter());
            $duplicatedCarConfiguration->setCar($carConfiguration->getCar());
            $duplicatedCarConfiguration->setValue($carConfiguration->getValue());
            $duplicatedRace->addCarConfiguration($duplicatedCarConfiguration);
            $this->getDoctrine()->getManager()->persist($duplicatedCarConfiguration);
        }
        $inscriptions = $this->getDoctrine()->getRepository(DriverRace::class)->findBy(['race'=>$race]);
        foreach ($inscriptions as $inscription){
            $duplicatedInscription = new DriverRace();
            $duplicatedInscription->setRace($duplicatedRace);
            $duplicatedInscription->setDriver($inscription->getDriver());
            $duplicatedInscription->setCar($inscription->getCar());
            $duplicatedInscription->setPool($inscription->getPool());
            $this->getDoctrine()->getManager()->persist($duplicatedInscription);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->updateAction($duplicatedRace, $request, false, true);
    }

    /**
     * @param Race $race
     * @param bool $isInscriptions
     * @return array
     */
    protected function getRaceViewParams(Race $race, $isInscriptions = false): array
    {
        $inscriptions = [];
        foreach ($race->getDriverRaces() as $inscription) {
            if ($inscription->getPool() instanceof Pool) {
                $inscriptions[$inscription->getPool()->getId()]['pool'] = $inscription->getPool();
                if($isInscriptions){
                    $inscriptions[$inscription->getPool()->getId()]['drivers'][] = $inscription;
                } else {
                    $inscriptions[$inscription->getPool()->getId()]['drivers'][] = $inscription;
                }
            } else {
                $inscriptions[0]['drivers'][] = $inscription;
            }
        }

        uasort(
            $inscriptions,
            static function ($a, $b) {
                return $a['pool']->getPriority() > $b['pool']->getPriority();
            }
        );
        $raceConfigurations = $race->getConfigurations()->toArray();
        uasort($raceConfigurations, static function (RaceConfiguration $a, RaceConfiguration $b) {
            return strcmp($a->getParameter()->getName(), $b->getParameter()->getName());
        });
        $race->setConfigurations($raceConfigurations);
        return [
            'race' => $race,
            'inscriptions' => $inscriptions,
        ];
    }
}