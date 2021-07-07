<?php

namespace App\Controller;

use App\Repository\TrafficRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrafficController extends AbstractController
{
    /**
     * @Route("/traffic", name="traffic")
     * @param TrafficRepository $trafficRepository
     * @return Response
     */
    public function index(TrafficRepository $trafficRepository): Response
    {
        $logByDays = [];
        $time = time();
        $unixTimestamp = $time - 60 * 60 * 24 * 31;
        $date = (new \DateTime)->setTimestamp($unixTimestamp);
        $i = 60 * 60 * 24;
        while ($unixTimestamp < $time) {
            $logByDays[substr((new \DateTime())->setTimestamp($unixTimestamp)->format(DATE_ATOM), 0, 10)] = [];
            $unixTimestamp += $i;
        }
        $max = 0;

        $logs = $trafficRepository->createQueryBuilder('traffic')
            ->where('traffic.date > :date')
            ->setParameter('date', $date)
            ->orderBy('traffic.date', 'asc')
            ->getQuery()
            ->getResult();
        foreach ($logs as $log) {
            $logByDays[substr($log->getDate()->format(DATE_ATOM), 0, 10)][] = $log;
        }
        foreach ($logByDays as $logs) {
            if (count($logs) > $max) {
                $max = count($logs);
            }
        }
        return $this->render('traffic/index.html.twig', [
            'logs' => $logByDays,
            'max' => $max
        ]);
    }
}
