<?php

namespace App\Repository;

use App\Entity\Updatepass;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Updatepass|null find($id, $lockMode = null, $lockVersion = null)
 * @method Updatepass|null findOneBy(array $criteria, array $orderBy = null)
 * @method Updatepass[]    findAll()
 * @method Updatepass[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpdatepassRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Updatepass::class);
    }

    // /**
    //  * @return Updatepass[] Returns an array of Updatepass objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Updatepass
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
