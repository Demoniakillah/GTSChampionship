<?php


namespace App\Controller\Admin;


use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\Race;
use App\Entity\Team;
use App\Form\RaceResultsType;
use App\Repository\RaceRepository;
use App\Repository\TeamRepository;
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
     * @Route("/results/{id}", name="admin_race_results", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function results(Race $race): Response
    {
        $form = $this->createForm(
            RaceResultsType::class,
            $race,
            ['driver_race_repository' => $this->getDoctrine()->getRepository(DriverRace::class)]
        );

        return $this->render(
            'admin/race_results.html.twig',
            [
                'race' => $race,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="admin_race_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Race $race): Response
    {
        $inscriptions = [];
        foreach ($race->getDriverRaces() as $inscription) {
            if ($inscription->getPool() instanceof Pool) {
                $inscriptions[$inscription->getPool()->getId()]['pool'] = $inscription->getPool();
                $inscriptions[$inscription->getPool()->getId()]['drivers'][] = $inscription->getDriver();
            } else {
                $inscriptions[0]['drivers'][] = $inscription->getDriver();
            }
        }

        return $this->render(
            'admin/race_show.html.twig',
            [
                'race' => $race,
                'inscriptions' => $inscriptions,
            ]
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/new", name="admin_race_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Race(), $request, true, true);
    }

    /**
     * @Route("/index",methods={"GET"}, name="race_admin_index")
     */
    public function index(RaceRepository $raceRepository): Response
    {
        $races = $raceRepository->findBy([], ['date' => 'desc']);
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
}