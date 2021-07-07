<?php

namespace App\Repository;

use App\Entity\RacePoolHost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RacePoolHost|null find($id, $lockMode = null, $lockVersion = null)
 * @method RacePoolHost|null findOneBy(array $criteria, array $orderBy = null)
 * @method RacePoolHost[]    findAll()
 * @method RacePoolHost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RacePoolHostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RacePoolHost::class);
    }

    // /**
    //  * @return RacePoolHost[] Returns an array of RacePoolHost objects
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
    public function findOneBySomeField($value): ?RacePoolHost
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
