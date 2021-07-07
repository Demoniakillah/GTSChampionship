<?php

namespace App\Controller;

use App\Controller\Admin\RaceController;
use App\Entity\Race;
use App\Entity\Terms;
use App\TwigExtension\RaceResult;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventViewController extends RaceController
{
    /**
     * @Route("/public/event/{id}", name="event_view", requirements={"id": "\d+"})
     * @param Race $race
     * @param RaceResult $raceResult
     * @return Response
     */
    public function displayEvent(Race $race, RaceResult $raceResult): Response
    {

        return $this->render('event_view/index.html.twig', array_merge(
            $this->getRaceViewParams($race, true),
            [
                'results' => $raceResult->getRaceResults($race->getDriverRaces()),
                'terms' => $this->getDoctrine()->getRepository(Terms::class)->findOneBy([])
            ]
        ));
    }
}
