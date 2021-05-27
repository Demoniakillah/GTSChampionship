<?php

namespace App\Controller\Api;

use App\Entity\Driver;
use App\Entity\Pool;
use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin/api/pool")
 */
class ApiPoolController extends AbstractController
{
    /**
     * @Route("/drivers", name="api_update_drivers_pool", methods={"POST"})
     */
    public function updateDriverPool(Request $request): JsonResponse
    {
        foreach ($request->request->get('pool_drivers') as $data) {
            $pool = $this->getDoctrine()->getRepository(Pool::class)->find($data['pool']);
            $driver = $this->getDoctrine()->getRepository(Driver::class)->find($data['driver']);
            if ($driver instanceof Driver) {
                $driver->setPool($pool);
            }
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }

    /**
     * @Route("/", name="api_update_pool_priority", methods={"POST"})
     */
    public function updatePoolPriority(Request $request): JsonResponse
    {
        foreach ($request->request->get('pools') as $pool) {
            $poolEntity = $this->getDoctrine()->getRepository(Pool::class)->find($pool['id']);
            if ($poolEntity instanceof Pool) {
                $poolEntity->setPriority((int)$pool['priority']);
            }
        }
        $this->getDoctrine()->getManager()->flush();

        return $this->json(true);
    }
}
