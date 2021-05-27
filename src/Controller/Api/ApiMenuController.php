<?php

namespace App\Controller\Api;

use App\Repository\MenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin/api/menu")
 */
class ApiMenuController extends AbstractController
{
    /**
     * @Route("/", name="api_get_menu", methods={"GET"})
     */
    public function getMenu(MenuRepository $menuRepository, SerializerInterface $serializer): JsonResponse
    {
        $output = [];
        foreach ($menuRepository->findBy([], ['label' => 'ASC']) as $item) {
            $add = false;
            if ($item->getRole() === 'ROLE_ADMIN' && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $add = true;
            }

            if ($item->getRole() === 'ROLE_SUPER_ADMIN' && in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
                $add = true;
            }
            if ($add) {
                $item->setUrl($this->generateUrl($item->getRoute()));
                $item = json_decode($serializer->serialize($item, 'json'), true);
                $output[] = $item;
            }
        }

        return $this->json($output);
    }
}
