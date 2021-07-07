<?php


namespace App\Form;


use App\Entity\DriverRace;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * RaceResultType
 * RaceResultType.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 20/05/2021
 */
class RaceResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', null, ['attr' => ['class' => 'hide driver_race_id']])
            ->add('finishPosition', TextType::class, ['attr' => ['class' => 'hide_with_span position']])
            ->add('driver', TextType::class, [
                'attr' => ['class' => 'hide_with_span']
            ])
            ->add('pool', TextType::class, [
                'attr' => ['class' => 'hide'],
                'data' => $options['pool_id']
            ])
            ->add('totalTime')
            ->add('bestLap')
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
            ->add('car', null, [
                'attr' => ['class' => 'car_select']
            ])
            ->add('bonus', null, ['empty_data' => 0, 'attr' => ['min' => 0]])
            ->add('penalty', null, ['empty_data' => 0, 'attr' => ['min' => 0]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DriverRace::class,
            ]
        )->setRequired('pool_id');
    }
}