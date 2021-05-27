<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Car;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\MakerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/car")
 */
class CarController extends MainController
{
    /**
     * @Route("/", name="car_index", methods={"GET"})
     */
    public function index(MakerRepository $makerRepository): Response
    {
        return $this->render('car/index.html.twig', [
            'makers' => $makerRepository->findBy([],['name'=>'asc']),
        ]);
    }

    /**
     * @Route("/new", name="car_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Car(), $request);
    }

    /**
     * @Route("/{id}", name="car_show", methods={"GET"})
     */
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="car_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Car $car): Response
    {
        return $this->updateAction($car, $request, false);
    }

    /**
     * @Route("/{id}", name="car_delete", methods={"POST"})
     */
    public function delete(Request $request, Car $car): Response
    {
        return $this->deleteAction($request,$car);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'car';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return CarType::class;
    }
}
