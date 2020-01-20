<?php

namespace App\Repository;

use App\Entity\Safran;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Safran|null find($id, $lockMode = null, $lockVersion = null)
 * @method Safran|null findOneBy(array $criteria, array $orderBy = null)
 * @method Safran[]    findAll()
 * @method Safran[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SafranRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Safran::class);
    }

    // /**
    //  * @return Safran[] Returns an array of Safran objects
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
    public function findOneBySomeField($value): ?Safran
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
