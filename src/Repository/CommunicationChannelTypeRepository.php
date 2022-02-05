<?php

namespace App\Repository;

use App\Entity\CommunicationChannelType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommunicationChannelType|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunicationChannelType|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunicationChannelType[]    findAll()
 * @method CommunicationChannelType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunicationChannelTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunicationChannelType::class);
    }

    // /**
    //  * @return CommunicationChannelType[] Returns an array of CommunicationChannelType objects
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
    public function findOneBySomeField($value): ?CommunicationChannelType
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
