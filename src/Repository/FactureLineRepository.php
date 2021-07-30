<?php

namespace App\Repository;

use App\Entity\FactureLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FactureLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method FactureLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method FactureLine[]    findAll()
 * @method FactureLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FactureLineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FactureLine::class);
    }

    // /**
    //  * @return FactureLine[] Returns an array of FactureLine objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FactureLine
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
