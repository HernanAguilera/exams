<?php

namespace App\Tests\Repository;

use App\Entity\Test;
use App\Entity\User;
use App\EntityFactories\ExamFactory;
use App\EntityFactories\TestFactory;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TestRepositoryTest extends KernelTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;
    private $faker;

    protected function setUp(): void {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()
                                   ->get('doctrine')
                                   ->getManager();
        $this->faker = Factory::create();
    }

    public function test_update_test() {
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->em);
        $user = $this->createUser();

        $test->setExam($exam);
        $test->setUser($user);

        $this->em->persist($test);
        $this->em->flush();

        $exam2 = ExamFactory::create($this->em);
        $user2 = $this->createUser();

        $date = date_format($this->faker->dateTimeBetween('+1 week', '+4 weeks'), 'Y-m-d');
        $test2 = $this->em->getRepository(Test::class)->updatedRegister($test, [
            'user' => $user2,
            'exam' => $exam2,
            'date' => new \DateTime($date)
        ]);

        $this->assertEquals($test->getId(), $test2->getId());
        $this->assertEquals($test2->getExam()->getId(), $exam2->getId());
        $this->assertEquals($test2->getUser()->getId(), $user2->getId());
        $this->assertEquals($test2->getDate(), new \DateTime($date));
    }

    private function createUser() 
    {
        $user = $this->em
                     ->getRepository(User::class)
                     ->createUser(
                         $this->faker->email(),
                         $this->faker->word()
                    );
        return $user;
    }
}
