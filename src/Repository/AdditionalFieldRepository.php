<?php

namespace App\Repository;

use App\Entity\AdditionalField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdditionalField|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdditionalField|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdditionalField[]    findAll()
 * @method AdditionalField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdditionalFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdditionalField::class);
    }

    // /**
    //  * @return AdditionalField[] Returns an array of AdditionalField objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdditionalField
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
