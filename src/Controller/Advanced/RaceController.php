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
use App\Entity\RacePoolCasting;
use App\Entity\RacePoolHost;
use App\Entity\RacePoolSaloonLabel;
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
            'user_group' => $this->getUser()->getUserGroup(),
            'pool_repository' => $this->getDoctrine()->getRepository(Pool::class),
            'race_parameters_repository' => $this->getDoctrine()->getRepository(RaceParameter::class),
            'maker_repository' => $this->getDoctrine()->getRepository(Maker::class),
            'country_repository' => $this->getDoctrine()->getRepository(Country::class),
        ];
    }

    /**
     * @Route("/", name="race_index", methods={"GET"})
     * @param RaceRepository $raceRepository
     * @return Response
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
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        if(isset($request->request->get('race')['name'])){
            $name = $request->request->get('race')['name'];
            $race = $this->getDoctrine()->getRepository(Race::class)->findOneByName($name);
            if($race instanceof Race){
                throw new \RuntimeException('Race already exists with this name');
            }
        }
        return $this->updateAction(new Race(), $request);

    }

    /**
     * @Route("/{id}", name="race_show", methods={"GET"}, requirements={"id"="\d+"})
     * @param Race $race
     * @return Response
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
     * @param Request $request
     * @param Race $race
     * @return Response
     */
    public function edit(Request $request, Race $race): Response
    {
        return $this->updateAction($race, $request, false);

    }

    /**
     * @param $poolId
     * @param Race $race
     * @param $value
     * @param $repositoryClass
     */
    protected function addRacePoolElement($poolId,Race $race, $value, $repositoryClass): void
    {
        $pool = $this->getDoctrine()->getRepository(Pool::class)->find($poolId);
        if($pool instanceof Pool){
            $poolElement = $this->getDoctrine()->getRepository($repositoryClass)->findOneBy(['race'=>$race,'pool'=>$pool]);
            if(!$poolElement instanceof $repositoryClass){
                $poolElement = new $repositoryClass();
            }
            $poolElement->setPool($pool)->setValue($value)->setRace($race);
            $this->getDoctrine()->getmanager()->persist($poolElement);
        }
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
            if (preg_match('/^pool_host_(\d+)$/', $field,$match)) {
                $this->addRacePoolElement($match[1],$entity,$value,RacePoolHost::class);
            }
            if (preg_match('/^pool_saloon_label_(\d+)$/', $field,$match)) {
                $this->addRacePoolElement($match[1],$entity,$value,RacePoolSaloonLabel::class);
            }
            if (preg_match('/^pool_casting_(\d+)$/', $field,$match)) {
                $this->addRacePoolElement($match[1],$entity,$value,RacePoolCasting::class);
            }
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
            $inscription = $this->getDoctrine()->getRepository(DriverRace::class)->findOneBy(
                ['driver' => $driver, 'race' => $entity]
            );
            if ($inscription instanceof DriverRace) {
                $inscription->setStartPosition($i + 1);
                if ($driver->getPool() instanceof Pool) {
                    $inscription->setPool($driver->getPool());
                }
                if ($defaultDriverCar instanceof Car) {
                    $inscription->setCar($defaultDriverCar);
                }
            }

        }
        $entityManager->persist($entity);
        $entityManager->flush();
    }

    /**
     * @Route("/{id}", name="race_delete", methods={"POST"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param Race $race
     * @return Response
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
