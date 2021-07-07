<?php


namespace App\Controller\Api;


use App\Entity\Car;
use App\Entity\Race;
use App\Entity\RaceCarConfiguration;
use App\Entity\RaceCarParameter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * ApiRaceCarConfigurationController
 * ApiRaceCarConfigurationController.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 16/05/2021
 * @Route("/api/racecarconfiguration")
 */
class ApiRaceCarConfigurationController extends AbstractController
{
    /**
     * @Route("/update", name="api_update_race_car_configuration", methods={"POST"})
     */
    public function updateConfigurations(Request $request): Response
    {
        $car = $this->getDoctrine()->getRepository(Car::class)->find($request->request->get('car'));
        $race = $this->getDoctrine()->getRepository(Race::class)->find($request->request->get('race'));
        if ($car instanceof Car && $race instanceof Race) {
            $configurations = array_filter(
                $request->request->all(),
                static function ($key) {
                    return preg_match('/race_car_configuration_[\d]+/', $key);
                },
                ARRAY_FILTER_USE_KEY
            );
            foreach ($this->getDoctrine()->getRepository(RaceCarConfiguration::class)->findBy(
                [
                    'car' => $car,
                    'race' => $race,
                ]
            ) as $raceCarConfiguration) {
                $this->getDoctrine()->getManager()->remove($raceCarConfiguration);
            }
            $this->getDoctrine()->getManager()->flush();

            foreach ($configurations as $configuration) {
                if (!empty($configuration['value'])) {
                    $raceCarParameter = $this->getDoctrine()->getRepository(RaceCarParameter::class)->find(
                        $configuration['parameter']
                    );
                    if ($raceCarParameter instanceof RaceCarParameter) {
                        $raceCarConfiguration = new RaceCarConfiguration();
                        $raceCarConfiguration
                            ->setRace($race)
                            ->setCar($car)
                            ->setParameter($raceCarParameter)
                            ->setValue($configuration['value']);
                        $this->getDoctrine()->getManager()->persist($raceCarConfiguration);
                    }
                }
            }
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->json(true);
    }

    /**
     * @Route("/get", name="api_get_race_car_configuration", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function getConfigurations(Request $request): Response
    {
        $output = [];
        $car = $this->getDoctrine()->getRepository(Car::class)->find($request->request->get('car'));
        $race = $this->getDoctrine()->getRepository(Race::class)->find($request->request->get('race'));
        if ($car instanceof Car && $race instanceof Race) {
            $raceCarParameters = $this->getDoctrine()->getRepository(RaceCarParameter::class)->findAll();
            foreach ($raceCarParameters as $raceCarParameter) {
                $output[$raceCarParameter->getId()] = [
                    'unity' => $raceCarParameter->getUnity(),
                    'parameter' => $raceCarParameter->getName(),
                    'configuration' => null,
                ];
            }
            $configurations = $this->getDoctrine()->getRepository(RaceCarConfiguration::class)->findBy(
                [
                    'car' => $car,
                    'race' => $race,
                ]
            );
            foreach ($configurations as $configuration) {
                $output[$configuration->getParameter()->getId()]['configuration'] = $configuration->getValue();
            }
        }

        return $this->render(
            'admin/race_car_configuration_form.html.twig',
            [
                'race' => $race->getId(),
                'car' => $car->getId(),
                'configurations' => $output,
            ]
        );
    }
}