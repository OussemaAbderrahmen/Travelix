<?php

namespace App\Repository;

use App\Entity\Categoryactivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Categoryactivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categoryactivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categoryactivity[]    findAll()
 * @method Categoryactivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryactivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categoryactivity::class);
    }

    // /**
    //  * @return Categoryactivity[] Returns an array of Categoryactivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Categoryactivity
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
