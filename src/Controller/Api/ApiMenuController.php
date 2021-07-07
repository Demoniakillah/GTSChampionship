<?php

namespace App\Controller\Api;

use App\Repository\MenuRepository;
use JsonException;
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
     * @param MenuRepository $menuRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @throws JsonException
     */
    public function getMenu(MenuRepository $menuRepository, SerializerInterface $serializer): JsonResponse
    {
        $output = [];
        foreach ($menuRepository->findBy([], ['label' => 'ASC']) as $item) {
            $add = false;
            if ($item->getRole() === 'ROLE_ADMIN' && in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
                $add = true;
                $classDependencies = explode(',', $item->getClassDependencies());
                foreach ($classDependencies as $classDependency) {
                    if (class_exists($classDependency) && count($this->getDoctrine()->getRepository($classDependency)->findBy(['userGroup' => $this->getUser()->getUserGroup()])) === 0) {
                        $add &= false;
                    }
                }
            }

            if ($item->getRole() === 'ROLE_SUPER_ADMIN' && in_array('ROLE_SUPER_ADMIN', $this->getUser()->getRoles())) {
                $add = true;
            }
            if ($add) {
                $route = $item->getRoute();
                $item->setUrl($this->generateUrl($route));
                $item = json_decode($serializer->serialize($item, 'json'), true, 512, JSON_THROW_ON_ERROR);
                $output[$route] = $item;
            }
        }

        return $this->json($output);
    }
}
