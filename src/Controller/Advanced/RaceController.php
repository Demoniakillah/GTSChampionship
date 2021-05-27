<?php

namespace App\Controller\Advanced;

use App\Controller\MainController;
use App\Entity\Car;
use App\Entity\Country;
use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Maker;
use App\Entity\Pool;
use App\Entity\Race;
use App\Entity\RaceConfiguration;
use App\Entity\RaceParameter;
use App\Form\RaceType;
use App\Repository\RaceRepository;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/advanced/race")
 */
class RaceController extends MainController
{
    /**
     * @return array
     */
    protected function getFormOptions(): array
    {
        return [
            'race_parameters_repository' => $this->getDoctrine()->getRepository(RaceParameter::class),
            'maker_repository' => $this->getDoctrine()->getRepository(Maker::class),
            'country_repository' => $this->getDoctrine()->getRepository(Country::class),
        ];
    }

    /**
     * @Route("/", name="race_index", methods={"GET"})
     */
    public function index(RaceRepository $raceRepository): Response
    {
        return $this->render(
            'race/index.html.twig',
            [
                'races' => $raceRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="race_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        return $this->updateAction(new Race(), $request);

    }

    /**
     * @Route("/{id}", name="race_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Race $race): Response
    {
        return $this->render(
            'race/show.html.twig',
            [
                'race' => $race,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="race_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, Race $race): Response
    {
        return $this->updateAction($race, $request, false);

    }

    /**
     * @param Request $request
     * @param Race $entity
     * @param FormInterface $form
     */
    protected function submit(Request $request, $entity, FormInterface $form): void
    {
        $entityManager = $this->getDoctrine()->getManager();
        $raceParametersFromForm = [];
        foreach ($request->request->get('race') as $field => $value) {
            $match = [];
            if (preg_match('/^race_parameter_(\d+)$/', $field, $match)) {
                [$full, $raceParameterId] = $match;
                $raceParametersFromForm[(int)$raceParameterId] = $value;
            }
        }
        $raceParameters = $this->getDoctrine()->getRepository(RaceParameter::class)->findAll();
        $raceConfigurations = [];
        foreach ($this->getDoctrine()->getRepository(RaceConfiguration::class)->findByRace(
            $entity
        ) as $raceConfiguration) {
            if ($raceConfiguration->getParameter() instanceof RaceParameter) {
                $raceConfigurations[$raceConfiguration->getParameter()->getId()] = $raceConfiguration;
            }
        }

        foreach ($raceParameters as $raceParameter) {
            $raceConfiguration = $raceConfigurations[$raceParameter->getId()] ?? new RaceConfiguration();
            $raceConfiguration->setParameter($raceParameter);
            $raceConfiguration->setRace($entity);
            $raceConfiguration->setValue($raceParametersFromForm[$raceParameter->getId()]);
            $entity->addConfiguration($raceConfiguration);
            $entityManager->persist($raceConfiguration);
        }
        $defaultDriverCar = current(current($entity->getCars()));
        foreach ($this->getDoctrine()->getRepository(Driver::class)->findAll() as $i => $driver) {
            $inscription = new DriverRace();
            $inscription
                ->setRace($entity)
                ->setDriver($driver)
                ->setStartPosition($i + 1);
            if($driver->getPool() instanceof Pool){
                $inscription->setPool($driver->getPool());
            }
            if($defaultDriverCar instanceof Car){
                $inscription->setCar($defaultDriverCar);
            }
            $entityManager->persist($inscription);
        }
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * @Route("/{id}", name="race_delete", methods={"POST"}, requirements={"id"="\d+"})
     */
    public function delete(Request $request, Race $race): Response
    {
        return $this->deleteAction($request, $race);

    }

    /**
     * @return string
     */
    protected function getName(): string
    {
        return 'race';
    }

    /**
     * @return string
     */
    protected function getFormTypeClass(): string
    {
        return RaceType::class;
    }
}
