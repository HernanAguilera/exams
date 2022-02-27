<?php

namespace App\Tests\Controller;

use App\EntityFactories\TestFactory;
use App\EntityFactories\ExamFactory;
use App\EntityFactories\CompanyFactory;
use App\Entity\User;
use App\EntityFactories\ScheduleFactory;
use App\Tests\ApiTestCase;

class ExamRegistrationControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->api_url = '/api/registration/exam/';
    }

    public function test_list_respond_correctly(): void
    {
        $n = 1;// rand(1, 20);
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->entityManager, $n);
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $user = $this->createUser();
        $this->entityManager->persist($schedule);

        $test->setExam($exam);
        $test->setUser($user);
        $test->setSchedule($schedule);

        $this->entityManager->persist($test);
        $this->entityManager->flush();
        
        $crawler = $this->get();

        $response = $this->getResponse();

        $this->assertEquals($n, count($response));
        $this->assertEquals($test->getId(), $response[0]['id']);
        $this->assertEquals($exam->getId(), $response[0]['exam']['id']);
        $this->assertEquals($user->getId(), $response[0]['user']['id']);
        $this->assertEquals($schedule->getId(), $response[0]['schedule']['id']);
    }

    public function test_create_a_exam_with_correct_data()
    {
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        $data = [
            'exam' => $exam->getId(),
            'user' => $user->getId(),
            'schedule' => $schedule->getId()
        ];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals($data['exam'], $response['exam']['id']);
        $this->assertEquals($data['user'], $response['user']['id']);
        $this->assertEquals($data['schedule'], $response['schedule']['id']);
    }

    public function test_create_a_exam_with_schedule_does_not_correspond_to_exam()
    {
        $exam = ExamFactory::create($this->entityManager);
        $exam2 = ExamFactory::create($this->entityManager);
        $user = $this->createUser();
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        $data = [
            'exam' => $exam2->getId(),
            'user' => $user->getId(),
            'schedule' => $schedule->getId()
        ];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        // dd($response);

        $this->assertResponseStatusCodeSame(400);
        $this->assertArrayHasKey('errors', $response);
    }

    public function test_trying_create_test_with_missing_data()
    {
        $data = [];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('exam', $response['errors']);
        $this->assertArrayHasKey('user', $response['errors']);
        $this->assertArrayHasKey('schedule', $response['errors']);
    }

    public function test_get_individual_existent_exam()
    {
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);

        $test->setExam($exam);
        $test->setUser($user);
        $test->setSchedule($schedule);

        $this->entityManager->persist($test);
        $this->entityManager->flush();

        $crawler = $this->get($this->api_url . $test->getId());
        $response = $this->getResponse();

        $this->assertEquals($test->getId(), $response['id']);
        $this->assertEquals($exam->getId(), $response['exam']['id']);
        $this->assertEquals($exam->getName(), $response['exam']['name']);
        $this->assertEquals($user->getId(), $response['user']['id']);
        $this->assertEquals($user->getEmail(), $response['user']['email']);
        $this->assertEquals($schedule->getId(), $response['schedule']['id']);
        $this->assertEquals(date_format($schedule->getDate(), 'Y-m-d'), $response['schedule']['date']);
        
    }

    public function test_get_individual_unexistent_exam()
    {
        $crawler = $this->get($this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }

    public function test_update_existent_exam_with_correct_data()
    {
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);

        $test->setExam($exam);
        $test->setUser($user);
        $test->setSchedule($schedule);

        $this->entityManager->persist($test);

        $exam2 = ExamFactory::create($this->entityManager);
        $user2 = $this->createUser();
        $schedule2 = ScheduleFactory::add();
        $schedule2->setExam($exam2);
        $this->entityManager->persist($schedule2);

        $this->entityManager->flush();

        $data = [
            'exam' => $exam2->getId(),
            'user' => $user2->getId(),
            'schedule' => $schedule2->getId()
        ];

        $crawler = $this->put($data, $this->api_url . $test->getId());
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals($data['exam'], $response['exam']['id']);
        $this->assertEquals($data['user'], $response['user']['id']);
        $this->assertEquals($data['schedule'], $response['schedule']['id']);
    }


    public function test_update_unexistent_exam_with_correct_data()
    {
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);

        $test->setExam($exam);
        $test->setUser($user);
        $test->setSchedule($schedule);

        $this->entityManager->persist($test);

        $exam2 = ExamFactory::create($this->entityManager);
        $user2 = $this->createUser();
        $schedule2 = ScheduleFactory::add();
        $schedule2->setExam($exam2);
        $this->entityManager->persist($schedule2);

        $this->entityManager->flush();

        $data = [
            'exam' => $exam2->getId(),
            'user' => $user2->getId(),
            'schedule' => $schedule2->getId()
        ];

        $crawler = $this->put($data, $this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }

    public function test_delete_existent_exam()
    {
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);

        $test->setExam($exam);
        $test->setUser($user);
        $test->setSchedule($schedule);

        $this->entityManager->persist($schedule);
        $this->entityManager->persist($test);
        $this->entityManager->flush();

        $crawler = $this->delete(null, $this->api_url . $test->getId());
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('message', $response);
    }

    public function test_delete_unexistent_exam()
    {
        $crawler = $this->delete(null, $this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }

    private function createUser() 
    {
        $user = $this->entityManager
                     ->getRepository(User::class)
                     ->createUser(
                         $this->faker->email(),
                         $this->faker->word()
                    );
        return $user;
    }
}
