<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\CarCategory;
use App\Form\CarCategoryType;
use App\Repository\CarCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/car/category")
 */
class CarCategoryController extends MainController
{
    /**
     * @Route("/", name="car_category_index", methods={"GET"})
     */
    public function index(CarCategoryRepository $carCategoryRepository): Response
    {
        return $this->render('car_category/index.html.twig', [
            'car_categories' => $carCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="car_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new CarCategory(), $request);
    }

    /**
     * @Route("/{id}", name="car_category_show", methods={"GET"})
     */
    public function show(CarCategory $carCategory): Response
    {
        return $this->render('car_category/show.html.twig', [
            'car_category' => $carCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="car_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CarCategory $carCategory): Response
    {
        return $this->updateAction($carCategory, $request, false);
    }

    /**
     * @Route("/{id}", name="car_category_delete", methods={"POST"})
     */
    public function delete(Request $request, CarCategory $carCategory): Response
    {
        return $this->deleteAction($request, $carCategory);
    }


    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'car_category';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return CarCategoryType::class;
    }
}
