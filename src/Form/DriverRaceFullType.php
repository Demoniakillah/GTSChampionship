<?php

namespace App\Form;

use App\Entity\DriverRace;
use App\Entity\Maker;
use App\Entity\Race;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverRaceFullType extends DriverRaceType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('pool')
            ->add('totalTime', null, ['empty_data' => '00:00:000'])
            ->add('bestLap', null, ['empty_data' => '00:00:000'])
            ->add('startPosition', null, ['empty_data' => 0])
            ->add('finishPosition', null, ['empty_data' => 0])
            ->add(
                'finishStatus',
                ChoiceType::class,
                [
                    'choices' => [
                        "FINISHED" => DriverRace::FINISHED,
                        "DISCONNECTED" => DriverRace::DISCONNECTED,
                        "MISSING" => DriverRace::MISSING,
                    ],
                ]
            )
            ->add('bonus', null, ['empty_data' => 0,'attr'=>['min'=>0]])
            ->add('penalty', null, ['empty_data' => 0,'attr'=>['min'=>0]])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DriverRace::class,
            ]
        ) ;
    }
}
