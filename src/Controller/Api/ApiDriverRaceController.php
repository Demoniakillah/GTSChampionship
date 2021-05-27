<?php

namespace App\Controller\Api;

use App\Entity\Car;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Pool;
use App\Entity\PoolConfiguration;
use App\Entity\Race;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/api/driver_race")
 */
class ApiDriverRaceController extends AbstractController
{
    /**
     * @Route("/finish/positions", name="api_update_driver_race_finish_positions", methods={"POST"})
     */
    public function updateDriversFinishPosition(Request $request): JsonResponse
    {
        foreach ($request->request->get('data') as $data) {
            $driverRace = $this->getDoctrine()->getRepository(DriverRace::class)->find($data['id']);
            if ($driverRace instanceof DriverRace) {
                $driverRace
                    ->setFinishPosition($data['data']['finishPosition'])
                    ->setTotalTime($data['data']['totalTime'] === '' ? '00:00:000' : $data['data']['totalTime'])
                    ->setBestLap($data['data']['bestLap'] === '' ? '00:00:000' : $data['data']['bestLap'])
                    ->setFinishStatus((int)$data['data']['finishStatus'])
                    ->setBonus((int)$data['data']['bonus'])
                    ->setPenalty((int)$data['data']['penalty']);
                $pool = $this->getDoctrine()->getRepository(Pool::class)->find($data['data']['pool']);
                if ($pool instanceof Pool) {
                    $driverRace->setPool($pool);
                }
                $car = $this->getDoctrine()->getRepository(Car::class)->find($data['data']['car']);
                if ($car instanceof Car) {
                    $driverRace->setCar($car);
                }
            }
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }

    /**
     * @Route("/positions", name="api_update_driver_race_positions", methods={"POST"})
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
                        $inscriptionEntity->setPool($pool)->setStartPosition((int)$inscription['position']);
                        if ($pool instanceof Pool && $inscriptionEntity->getDriver() instanceof Driver) {
                            $inscriptionEntity->getDriver()->setPool($pool);
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
