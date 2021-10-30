<?php

namespace App\Repository;

use App\Entity\S4CarList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method S4CarList|null find($id, $lockMode = null, $lockVersion = null)
 * @method S4CarList|null findOneBy(array $criteria, array $orderBy = null)
 * @method S4CarList[]    findAll()
 * @method S4CarList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class S4CarListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, S4CarList::class);
    }

    // /**
    //  * @return S4CarList[] Returns an array of S4CarList objects
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
    public function findOneBySomeField($value): ?S4CarList
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
