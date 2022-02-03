<?php

namespace App\Repository;

use App\Entity\TypeFieldContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeFieldContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeFieldContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeFieldContent[]    findAll()
 * @method TypeFieldContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeFieldContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeFieldContent::class);
    }

    // /**
    //  * @return TypeFieldContent[] Returns an array of TypeFieldContent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeFieldContent
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
