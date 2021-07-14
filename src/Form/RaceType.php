<?php

namespace App\Form;

use App\Entity\Pool;
use App\Entity\Race;
use App\Entity\RaceParameter;
use App\Repository\CountryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceType extends AbstractType
{
    protected function getPoolElementValue(Pool $pool, $list)
    {
        $data = false;
        foreach ($list as $poolElement) {
            if ($poolElement->getPool() instanceof Pool && $poolElement->getPool()->getId() === $pool->getId()) {
                $data = $poolElement->getValue();
                break;
            }
        }

        return $data;
    }

    /**
     * @throws \JsonException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('date')
            ->add('isEndurance')
            ->add('imageUrl')
            ->add('moreDetails')
            ->add('livery');

        foreach ($options['pool_repository']->findBy(['userGroup'=>$options['user_group']], ['priority' => 'asc']) as $pool) {
            $builder->add('pool_host_' . $pool->getId(), TextType::class, [
                'required'=>true,
                'mapped' => false,
                'label' => 'Pool host: ' . $pool->getname(),
                'data' => $this->getPoolElementValue($pool, $options['data']->getPoolHosts())
            ]);
            $builder->add('pool_casting_' . $pool->getId(), TextType::class, [
                'required'=>false,
                'mapped' => false,
                'label' => 'Pool casting: ' . $pool->getname(),
                'data' => $this->getPoolElementValue($pool, $options['data']->getPoolCastings())
            ]);
            $builder->add('pool_saloon_label_' . $pool->getId(), TextType::class, [
                'required'=>false,
                'mapped' => false,
                'label' => 'Pool saloon: ' . $pool->getname(),
                'data' => $this->getPoolElementValue($pool, $options['data']->getPoolSaloonLabels())
            ]);
        }
        $countries = [];
        foreach ($options['country_repository']->findAll() as $country) {
            foreach ($country->getTracks() as $track) {
                $countries[$country->getName()][] = $track;
            }
        }
        $builder->add('track', null, [
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
        foreach ($options['race_parameters_repository']->findBy([], ['name' => 'asc']) as $raceParameter) {
            if ($raceParameter instanceof RaceParameter) {
                if ($raceParameter->getType() === 'bool') {
                    $builder->add(
                        "race_parameter_" . $raceParameter->getId(),
                        CheckboxType::class,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => (bool)($configurations[$raceParameter->getId()] ?? $raceParameter->getAvailableValues()),
                        ]
                    );
                }
                if ($raceParameter->getType() === 'int') {
                    if ($raceParameter->getName() === 'Laps') {
                        $class = 'laps';
                    } else {
                        $class = '';
                    }
                    $builder->add(
                        "race_parameter_" . $raceParameter->getId(),
                        IntegerType::class,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => $configurations[$raceParameter->getId()] ?? (int)$raceParameter->getAvailableValues(),
                            'attr' => ['class' => $class]
                        ]
                    );
                }
                if ($raceParameter->getType() === 'string') {
                    if ($raceParameter->getName() === 'Laps') {
                        $class = 'laps';
                    } elseif ($raceParameter->getName() === 'Time') {
                        $class = 'endurance_time';
                    } else {
                        $class = '';
                    }
                    $builder->add(
                        "race_parameter_" . $raceParameter->getId(),
                        null,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => $configurations[$raceParameter->getId()] ?? $raceParameter->getAvailableValues(),
                            'attr' => ['class' => $class]
                        ]
                    );
                }
                if ($raceParameter->getType() === 'array') {
                    $builder->add(
                        "race_parameter_" . $raceParameter->getId(),
                        ChoiceType::class,
                        [
                            'mapped' => false,
                            'label' => $raceParameter->getName(),
                            'data' => $configurations[$raceParameter->getId()] ?? 0,
                            'choices' => array_flip(json_decode($raceParameter->getAvailableValues(), true, 512, JSON_THROW_ON_ERROR)),
                            'attr' => ['class'=>$raceParameter->getName() === 'Category'?'car_category':''],
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
            ->setRequired('user_group')
            ->setRequired('race_parameters_repository')
            ->setRequired('maker_repository')
            ->setRequired('pool_repository')
            ->setRequired('country_repository');
    }
}
