<?php

namespace App\Repository;

use App\Entity\RacePoolSaloonLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RacePoolSaloonLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method RacePoolSaloonLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method RacePoolSaloonLabel[]    findAll()
 * @method RacePoolSaloonLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacePoolSaloonLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RacePoolSaloonLabel::class);
    }

    // /**
    //  * @return RacePoolSaloonLabel[] Returns an array of RacePoolSaloonLabel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RacePoolSaloonLabel
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
