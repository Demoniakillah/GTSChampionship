<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/country")
 */
class CountryController extends MainController
{
    /**
     * @Route("/", name="country_index", methods={"GET"})
     */
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('country/index.html.twig', [
            'countries' => $countryRepository->findBy([],['name'=>'asc']),
        ]);
    }

    /**
     * @Route("/new", name="country_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Country(), $request);
    }

    /**
     * @Route("/{id}", name="country_show", methods={"GET"})
     */
    public function show(Country $country): Response
    {
        return $this->render('country/show.html.twig', [
            'country' => $country,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="country_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Country $country): Response
    {
        return $this->updateAction($country, $request, false);
    }

    /**
     * @Route("/{id}", name="country_delete", methods={"POST"})
     */
    public function delete(Request $request, Country $country): Response
    {
        return $this->deleteAction($request,$country);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'country';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return CountryType::class;
    }
}
