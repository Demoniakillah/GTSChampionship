<?php


namespace App\Form;


use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Race;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * RaceResultsType
 * RaceResultsType.php
 * @author Roy-Glen RAYMOND
 * royglen.raymond@digitalvirgo.com
 * created at 20/05/2021
 */
class RaceResultsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $driverRaces = $options['data']->getDriverRaces();
        $iterator = $driverRaces->getIterator();
        $iterator->uasort(function (DriverRace $a, DriverRace$b) {
            return ($a->getFinishPosition() < $b->getFinishPosition()) ? -1 : 1;
        });
        $collection = new ArrayCollection(iterator_to_array($iterator));
        $options['data']->setDriverRaces($collection->toArray());
        $builder->add(
            'driverRaces',
            CollectionType::class,
            [
                'entry_type' => RaceResultType::class,
                'entry_options' => ['driver_race_repository' => $options['driver_race_repository']],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Race::class,
            ]
        )->setRequired('driver_race_repository');
    }
}