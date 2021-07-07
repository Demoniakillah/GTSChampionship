<?php

namespace App\Repository;

use App\Entity\RacePoolCasting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RacePoolCasting|null find($id, $lockMode = null, $lockVersion = null)
 * @method RacePoolCasting|null findOneBy(array $criteria, array $orderBy = null)
 * @method RacePoolCasting[]    findAll()
 * @method RacePoolCasting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacePoolCastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RacePoolCasting::class);
    }

    // /**
    //  * @return RacePoolCasting[] Returns an array of RacePoolCasting objects
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
    public function findOneBySomeField($value): ?RacePoolCasting
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
