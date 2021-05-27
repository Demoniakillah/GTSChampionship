<?php

namespace App\Repository;

use App\Entity\RaceConfiguration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceConfiguration|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceConfiguration|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceConfiguration[]    findAll()
 * @method RaceConfiguration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceConfigurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceConfiguration::class);
    }

    // /**
    //  * @return RaceConfiguration[] Returns an array of RaceConfiguration objects
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
    public function findOneBySomeField($value): ?RaceConfiguration
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
