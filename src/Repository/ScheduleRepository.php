<?php

namespace App\Repository;

use App\Entity\Exam;
use App\Entity\Schedule;
use App\Traits\EntityRepositoryTrait;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Schedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method Schedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method Schedule[]    findAll()
 * @method Schedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScheduleRepository extends ServiceEntityRepository implements MetadataInterface
{
    use EntityRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Schedule::class);
    }

    /**
     * @return Schedule[] Returns an array of Schedule objects
     */
    public function search($params)
    {
        $query = $this->createQueryBuilder('s');
        if (key_exists('start_date', $params)){
            $query = $query->andWhere('s.date >= :start_date')
                           ->setParameter('start_date', $params['start_date']);
        }
        if (key_exists('end_date', $params)){
            $query = $query->andWhere('s.date <= :end_date')
                           ->setParameter('end_date', $params['end_date']);
        }
        if (key_exists('exam', $params)){
            $query = $query->leftjoin('s.exam', 'e')
                           ->andWhere('e.name LIKE :exam')
                           ->setParameter('exam',"%".$params['exam']."%");
        }

        return $query->orderBy('s.id', 'ASC')
                    // ->setMaxResults(10)
                    ->getQuery()
                    ->getResult()
                    ;
    }

    public function createSchedule(Exam $exam, DateTime $date): ?Schedule
    {
        $schedule = new Schedule;
        $schedule->setExam($exam);
        $schedule->setDate($date);
        $this->_em->persist($schedule);
        $this->_em->flush();
        return $schedule;
    }

    public function updateSchedule(Schedule $schedule, ?Exam $exam, ?DateTime $date): ?Schedule
    {
        if (!is_null($exam))
            $schedule->setExam($exam);
        if (!is_null($date))
            $schedule->setDate($date);
        $this->_em->persist($schedule);
        $this->_em->flush();
        return $schedule;
    }

    /*
    public function findOneBySomeField($value): ?Schedule
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
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
                'class' => Exam::class,
                'field' => 'exam',
                'type' => 'ManyToOne'
            ]
        ];
    }
}
