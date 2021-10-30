<?php

namespace App\Repository;

use App\Entity\S4CarSelect;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method S4CarSelect|null find($id, $lockMode = null, $lockVersion = null)
 * @method S4CarSelect|null findOneBy(array $criteria, array $orderBy = null)
 * @method S4CarSelect[]    findAll()
 * @method S4CarSelect[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class S4CarSelectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, S4CarSelect::class);
    }

    // /**
    //  * @return S4CarSelect[] Returns an array of S4CarSelect objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?S4CarSelect
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
