<?php

namespace App\Repository;

use App\Entity\PoolParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PoolParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method PoolParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method PoolParameter[]    findAll()
 * @method PoolParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PoolParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PoolParameter::class);
    }

    // /**
    //  * @return PoolParameter[] Returns an array of PoolParameter objects
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
    public function findOneBySomeField($value): ?PoolParameter
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
