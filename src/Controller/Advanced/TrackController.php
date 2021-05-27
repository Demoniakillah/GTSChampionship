<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Track;
use App\Form\TrackType;
use App\Repository\CountryRepository;
use App\Repository\TrackRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/track")
 */
class TrackController extends MainController
{
    /**
     * @Route("/", name="track_index", methods={"GET"})
     */
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render(
            'track/index.html.twig',
            [
                'countries' => $countryRepository->findBy([],['name'=>'asc']),
            ]
        );
    }

    /**
     * @Route("/new", name="track_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Track(), $request);
    }

    /**
     * @Route("/{id}", name="track_show", methods={"GET"})
     */
    public function show(Track $track): Response
    {
        return $this->render(
            'track/show.html.twig',
            [
                'track' => $track,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="track_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Track $track): Response
    {
        return $this->updateAction($track, $request, false);
    }

    /**
     * @Route("/{id}", name="track_delete", methods={"POST"})
     */
    public function delete(Request $request, Track $track): Response
    {
        return $this->deleteAction($request, $track);
    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'track';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return TrackType::class;
    }
}
