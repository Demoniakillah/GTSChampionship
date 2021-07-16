<?php

namespace App\Form;

use App\Entity\DriverRace;
use App\Repository\PoolRepository;
use App\Repository\RaceRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DriverRaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $userGroup = $options['user_group'];
        $builder
            ->add('race', null, [
                'attr' => ['required' => "required"], 'required' => true,
                'query_builder' => static function (RaceRepository $raceRepository) use ($userGroup) {
                    return $raceRepository->createQueryBuilder('race')
                        ->where('race.date > :now')
                        ->andWhere('race.userGroup = :userGroup')
                        ->setParameter('userGroup', $userGroup)
                        ->setParameter('now', new \DateTime())
                        ->orderBy('race.date', 'asc');
                }
            ])
            ->add('driver', null, ['attr' => ['required' => "required"], 'required' => true, 'data' => null])
            ->add('pool', null, [
                'query_builder' => static function (PoolRepository $poolRepository) use ($userGroup) {
                    return $poolRepository->createQueryBuilder('pool')
                        ->where('pool.userGroup = :userGroup')
                        ->setParameter('userGroup', $userGroup)
                        ->orderBy('pool.priority', 'asc');
                }
            ])
            ->add('car', null, ['data' => null])
            ->add('id', HiddenType::class, ['mapped' => false, 'data' => $options['data']->getId()]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => DriverRace::class,

            ]
        )->setRequired('user_group');
    }
}
