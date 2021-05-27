<?php

namespace App\Form;

use App\Entity\Race;
use App\Entity\RaceParameter;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('date')
            ->add('casting')
            ->add('host')
            ->add('moreDetails')
            ->add('livery');
        $countries = [];
        foreach ($options['country_repository']->findAll() as $country) {
            foreach ($country->getTracks() as $track) {
                $countries[$country->getName()][] = $track;
            }
        }
        $builder->add('track',null,[
            'choices' => $countries
        ]);
        $cars = [];
        foreach ($options['maker_repository']->findAll() as $maker) {
            foreach ($maker->getCars() as $car) {
                $cars[$maker->getName()][] = $car;
            }
        }
        $builder->add(
            'cars',
            null,
            [
                'choices' => $cars,
                'expanded' => false,
                'multiple' => true,
            ]
        );
        $configurations = [];
        foreach ($options['data']->getConfigurations() as $configuration) {
            $configurations[$configuration->getParameter()->getId()] = $configuration->getValue();
        }
        foreach ($options['race_parameters_repository']->findBy([], ['id' => 'asc']) as $raceParameter) {
            if ($raceParameter instanceof RaceParameter) {
                if ($raceParameter->getType() === 'int') {
                    $builder->add(
                        "race_parameter_".$raceParameter->getId(),
                        IntegerType::class,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => $configurations[$raceParameter->getId(
                                )] ?? (int)$raceParameter->getAvailableValues(),
                        ]
                    );
                }
                if ($raceParameter->getType() === 'string') {
                    $builder->add(
                        "race_parameter_".$raceParameter->getId(),
                        null,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => $configurations[$raceParameter->getId()] ?? $raceParameter->getAvailableValues(),
                        ]
                    );
                }
                if ($raceParameter->getType() === 'array') {
                    $builder->add(
                        "race_parameter_".$raceParameter->getId(),
                        ChoiceType::class,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => $configurations[$raceParameter->getId()] ?? 0,
                            'choices' => array_flip(json_decode($raceParameter->getAvailableValues(), true)),
                        ]
                    );
                }
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Race::class,
            ]
        )
            ->setRequired('race_parameters_repository')
            ->setRequired('maker_repository')
            ->setRequired('country_repository')
        ;
    }
}
