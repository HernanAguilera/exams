<?php

namespace App\Repository;

use App\Entity\QuestionQuestionField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method QuestionQuestionField|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionQuestionField|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionQuestionField[]    findAll()
 * @method QuestionQuestionField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionQuestionFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionQuestionField::class);
    }

    // /**
    //  * @return QuestionQuestionField[] Returns an array of QuestionQuestionField objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuestionQuestionField
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
