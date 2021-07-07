<?php

namespace App\Form;

use App\Entity\Pool;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PoolType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * @throws \JsonException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
        ;

        $maxPool = (int)$options['pool_configuration_repository']->findOneByName('max_pool')->getValue();
        $availablePriority = [];
        for ($i = 0; $i < $maxPool; $i++) {
            $availablePriority[$i] = $i;
        }
        foreach ($options['pool_repository']->findBy(['userGroup'=>$options['user_group']]) as $pool) {
            if (isset($availablePriority[(int)$pool->getPriority()])) {
                unset($availablePriority[(int)$pool->getPriority()]);
            }
        }
        $availablePriority[$options['data']->getPriority()] = $options['data']->getPriority();
        ksort($availablePriority);

        $builder->add(
            'priority',
            ChoiceType::class,
            [
                'choices' => $availablePriority,
                'data' => $options['data']->getPriority(),
                'attr' => ['required'=>'required']
            ]
        );

        if (is_array($options['data']->getPoints())) {
            $pointTable = $options['data']->getPoints();
        } else {
            $factor = (double)$options['pool_configuration_repository']->findOneByName('points_factor')->getValue();
            $last = $options['pool_repository']->findOneBy([],['id'=>'desc','priority'=>'desc'],1);
            if($last instanceof Pool) {
                $pointTable = $last->getPoints();
                foreach ($pointTable as $index => $point) {
                    $pointTable[$index] = (int)($point * $factor);
                }
            } else {
                $pointTable = json_decode($options['pool_configuration_repository']->findOneByName('base_points')->getValue(), true, 512, JSON_THROW_ON_ERROR);
            }
        }

        $maxDrivers = (int)$options['pool_configuration_repository']->findOneByName('max_drivers')->getValue();
        foreach ($pointTable as $position => $point) {
            if($position-$maxDrivers < 0) {
                $builder->add(
                    "points_$position",
                    IntegerType::class,
                    [
                        'label' => 'Position '.($position + 1),
                        'mapped' => false,
                        'data' => $point,
                        'empty_data' => 0,
                        'block_name' => "points[$position]",
                    ]
                );
            }
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Pool::class,
            ]
        )
            ->setRequired('user_group')
            ->setRequired('pool_repository')
            ->setRequired('pool_configuration_repository');
    }
}
