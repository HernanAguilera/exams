<?php

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Exam;
use App\Traits\EntityRepositoryTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Exam|null find($id, $lockMode = null, $lockVersion = null)
 * @method Exam|null findOneBy(array $criteria, array $orderBy = null)
 * @method Exam[]    findAll()
 * @method Exam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamRepository extends ServiceEntityRepository implements MetadataInterface
{
    use EntityRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exam::class);
    }

    /**
     * @return Exam[] Returns an array of Exam objects
     */
    public function search($params): array
    {
        $query = $this->createQueryBuilder('e');
        if (key_exists('name', $params)){
            $query = $query->andWhere('e.name LIKE :name')
                           ->setParameter('name', "%".$params['name']."%");
        }
        if (key_exists('company', $params)){
            $query = $query->leftjoin('e.companies', 'c')
                           ->andWhere('c.commercial_name LIKE :company')
                           ->setParameter('company',"%".$params['company']."%");
        }

        return $query->orderBy('e.id', 'ASC')
                    // ->setMaxResults(10)
                    ->getQuery()
                    ->getResult()
                    ;

        // return $this->createQueryBuilder('e')
        //     ->andWhere('e.exampleField = :val')
        //     ->setParameter('val', $value)
        //     ->orderBy('e.id', 'ASC')
        //     ->setMaxResults(10)
        //     ->getQuery()
        //     ->getResult()
        // ;
    }

    /*
    public function findOneBySomeField($value): ?Exam
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function completeFields($object): void
    {
        
    }

    public static function getRelations(): array
    {
        return [
            [
                'class' => Company::class,
                'field' => 'companies',
                'type' => 'ManyToMany'
            ]
        ];
    }
}
