<?php

namespace App\Repository;

use App\Entity\PoolConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PoolConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method PoolConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method PoolConfiguration[]    findAll()
 * @method PoolConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoolConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoolConfiguration::class);
    }

    // /**
    //  * @return PoolConfiguration[] Returns an array of PoolConfiguration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PoolConfiguration
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
