<?php

namespace App\Repository;

use App\Entity\Exam;
use App\Entity\Test;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function registerUserToExam(User $user, Exam $exam, DateTime $date): Test
    {
        $test = new Test;
        $test->setUser($user);
        $test->setExam($exam);
        $test->setDate($date);
        $test->setStatus(Test::RESERVED);
        $test->setAttended(false);
        $this->_em->persist($test);
        $this->_em->flush();
        return $test;
    }

    public function updatedRegister(Test $test, Array $data): ?Test
    {
        if ($test->getStatus() !== Test::RESERVED){
            throw new Exception("The reservation can't be modified because it's in {$test->getStatus()} status");
        }

        if (key_exists('user', $data) && ($data['user'] instanceof User))
            $test->setUser($data['user']);
        if (key_exists('exam', $data) && ($data['exam'] instanceof User))
            $test->setUser($data['exam']);
        if (key_exists('date', $data))
            $test->setUser($data['date']);

        $test->setStatus(Test::RESERVED);
        $test->setAttended(false);
        $this->_em->persist($test);
        $this->_em->flush();
        return $test;
    }

    public function cancelRegister(Test $test): ?Test
    {
        if ($test->getStatus() !== Test::RESERVED){
            throw new Exception("The reservation can't be modified because it's in {$test->getStatus()} status");
        }

        $test->setStatus(Test::CANCELED);
        $this->_em->persist($test);
        $this->_em->flush();
        return $test;
    }

    public function setInProgress(Test $test): Test
    {
        if ($test->getStatus() !== Test::RESERVED){
            throw new Exception("The reservation can't be modified because it's in {$test->getStatus()} status");
        }

        $test->setStatus(Test::IN_PROGRESS);
        $this->_em->persist($test);
        $this->_em->flush();

        return $test;
    }

    public function setFinishied(Test $test): Test
    {
        if ($test->getStatus() !== Test::IN_PROGRESS){
            throw new Exception("The reservation can't be modified because it's in {$test->getStatus()} status");
        }

        $test->setStatus(Test::FINISHIED);
        $this->_em->persist($test);
        $this->_em->flush();

        return $test;
    }

    public function  checkAttendanceRegister(Test $test): ?Test
    {
        if ($test->getStatus() === Test::CANCELED){
            throw new Exception("The reservation can't be modified because it's in {$test->getStatus()} status");
        }

        if(!$test->getAttended()){
            $test->setAttended(true);
            $this->_em->persist($test);
            $this->_em->flush();
        }
        return $test;
    }

    public function  checkUnattendanceRegister(Test $test): ?Test
    {
        if ($test->getStatus() === Test::CANCELED){
            throw new Exception("The reservation can't be modified because it's in {$test->getStatus()} status");
        }

        if($test->getAttended()){
            $test->setAttended(false);
            $this->_em->persist($test);
            $this->_em->flush();
        }
        return $test;
    }

    // /**
    //  * @return Test[] Returns an array of Test objects
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
    public function findOneBySomeField($value): ?Test
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
