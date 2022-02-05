<?php

namespace App\Repository;

use App\Entity\CommunicationChannel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommunicationChannel|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunicationChannel|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunicationChannel[]    findAll()
 * @method CommunicationChannel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunicationChannelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunicationChannel::class);
    }

    // /**
    //  * @return CommunicationChannel[] Returns an array of CommunicationChannel objects
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
    public function findOneBySomeField($value): ?CommunicationChannel
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
