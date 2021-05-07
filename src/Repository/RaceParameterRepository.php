<?php

namespace App\Repository;

use App\Entity\RaceParameter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RaceParameter|null find($id, $lockMode = null, $lockVersion = null)
 * @method RaceParameter|null findOneBy(array $criteria, array $orderBy = null)
 * @method RaceParameter[]    findAll()
 * @method RaceParameter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RaceParameterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RaceParameter::class);
    }

    // /**
    //  * @return RaceParameter[] Returns an array of RaceParameter objects
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
    public function findOneBySomeField($value): ?RaceParameter
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
