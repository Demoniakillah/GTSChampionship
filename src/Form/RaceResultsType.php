<?php


namespace App\Form;


use App\Entity\Driver;
use App\Entity\DriverRace;
use App\Entity\Pool;
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
        $poolId = $options['pool_id'];
        $driverRaces = $driverRaces->filter(
            static function ($driverRace) use ($poolId){
                return
                    $driverRace->getPool() instanceof Pool
                    &&
                    $driverRace->getPool()->getId() === $poolId
                    ;
        });
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
                'entry_options' => ['pool_id'=>$poolId],
                'data' => $collection->toArray()
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Race::class,
            ]
        )->setRequired('pool_id');
    }
}