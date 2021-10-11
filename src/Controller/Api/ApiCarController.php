<?php

namespace App\Controller\Api;

use App\Entity\CarCategory;
use App\Repository\CarCategoryRepository;
use App\Repository\MakerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiCarController extends AbstractController
{
    /**
     * @Route("/get/cars/by/category", name="api_get_cars_by_category", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function updateDriverRaceCar(Request $request, CarCategoryRepository $carCategoryRepository, MakerRepository $makerRepository): JsonResponse
    {
        $output = [];
        if ($request->request->has('category') && $request->request->get('category') !== 0) {
            $carCategory = $carCategoryRepository->find($request->request->get('category'));
            if ($carCategory instanceof CarCategory) {
                foreach ($carCategory->getCars() as $car) {
                    $output[$car->getMaker()->getName()][$car->getId()] = (string)$car;
                }
            }
        } else {
            foreach ($makerRepository->findAll() as $maker) {
                foreach ($maker->getCars() as $car) {
                    $output[$maker->getName()][$car->getId()] = (string)$car;
                }
            }
        }
        return $this->json($output);
    }
}