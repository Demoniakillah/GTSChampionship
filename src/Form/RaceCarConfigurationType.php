<?php

namespace App\Form;

use App\Entity\Race;
use App\Entity\RaceCarConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RaceCarConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('race')
            ->add('car')
            ->add('parameter')
            ->add('value')
        ;
        if($options['data']->getRace() instanceof Race){
            dd('COUCOCUCOU');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RaceCarConfiguration::class,
        ]);
    }
}
