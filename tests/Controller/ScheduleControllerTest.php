<?php

namespace App\Tests\Controller;

use App\EntityFactories\ScheduleFactory;
use App\EntityFactories\ExamFactory;
use App\Tests\ApiTestCase;
use DateTime;

class ScheduleControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->api_url = '/api/schedule/';
    }

    public function test_list_respond_correctly(): void
    {
        $n = rand(1, 20);
        $exam = ExamFactory::create($this->entityManager);
        $schedules = ScheduleFactory::add($n);
        if (!is_array($schedules)) {
            $schedules = [$schedules];
        }
        foreach($schedules as $schedule) {
            $schedule->setExam($exam);
            $this->entityManager->persist($schedule);
        }
        $this->entityManager->flush();

        $crawler = $this->get();

        $response = $this->getResponse();

        $this->assertEquals($n, count($response));
        $this->assertArrayHasKey('date', $response[0]);
        $this->assertArrayHasKey('exam', $response[0]);
        // $this->assertEquals(count($schedules[0]->getCompanies()), count($response[0]['companies']));
    }

    public function test_list_filter_respond_correctly(): void
    {
        $exam = ExamFactory::create($this->entityManager);

        $schedule1 = ScheduleFactory::add();
        $schedule1->setExam($exam);
        $datetime = new \DateTime();
        $datetime->sub(new \DateInterval('P1W'));
        $schedule1->setDate($datetime);
        $this->entityManager->persist($schedule1);
        $schedule2 = ScheduleFactory::add();
        $schedule2->setExam($exam);
        $datetime = new \DateTime();
        $datetime->sub(new \DateInterval('P2W'));
        $schedule2->setDate($datetime);
        $this->entityManager->persist($schedule2);
        $schedule3 = ScheduleFactory::add();
        $schedule3->setExam($exam);
        $datetime = new \DateTime();
        $datetime->sub(new \DateInterval('P3W'));
        $schedule3->setDate($datetime);
        $this->entityManager->persist($schedule3);

        $this->entityManager->flush();

        $exam = $exam->getName();
        $exam = substr($exam, 0, strlen($exam)-2);
        $start_date = date('Y-m-d', strtotime('-10 days'));
        $end_date = date('Y-m-d', strtotime('-4 days'));

        $crawler = $this->get("{$this->api_url}?start_date={$start_date}&end_date={$end_date}&exam={$exam}");

        $response = $this->getResponse();

        $this->assertArrayHasKey('date', $response[0]);
        $this->assertArrayHasKey('exam', $response[0]);
        $this->assertEquals(1, count($response));
    }

    public function test_create_a_schedule_with_correct_data()
    {
        $exam = ExamFactory::create($this->entityManager);
        $data = [
            'date' => date_format($this->faker->dateTimeBetween('+1 week', '+4 weeks'), 'Y-m-d'),
            'exam' => $exam->getId()
        ];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        // dd($response);

        $this->assertArrayHasKey('exam', $response);
        $this->assertEquals($exam->getName(), $response['exam']['name']);
        $this->assertEquals($data['date'], $response['date']);
    }

    public function test_trying_create_schedule_with_missing_data()
    {
        $data = [];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('date', $response['errors']);
        $this->assertArrayHasKey('exam', $response['errors']);
    }

    public function test_get_individual_existent_schedule()
    {
        $exam = ExamFactory::create($this->entityManager);
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        $crawler = $this->get($this->api_url . $schedule->getId());
        $response = $this->getResponse();

        $this->assertEquals($schedule->getId(), $response['id']);
        $this->assertArrayHasKey('date', $response);
        $this->assertEquals(date_format($schedule->getDate(), 'Y-m-d'), $response['date']);
        $this->assertEquals($schedule->getExam()->getId(), $response['exam']['id']);
    }

    public function test_get_individual_unexistent_schedule()
    {
        $crawler = $this->get($this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }

    public function test_update_existent_schedule_with_correct_data()
    {
        $exam = ExamFactory::create($this->entityManager);
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        $exam2 = ExamFactory::create($this->entityManager);
        $data = [
            'date' => date_format($this->faker->dateTimeBetween('+1 week', '+4 weeks'), 'Y-m-d'),
            'exam' => $exam2->getId()
        ];

        $crawler = $this->put($data, $this->api_url . $schedule->getId());
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('date', $response);
        $this->assertEquals($data['date'], $response['date']);
        $this->assertEquals($data['exam'], $response['exam']['id']);
    }

    public function test_update_unexistent_schedule_with_correct_data()
    {
        $data = [
            'date' => date_format($this->faker->dateTimeBetween('+1 week', '+4 weeks'), 'Y-m-d'),
        ];

        $crawler = $this->put($data, $this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }

    public function test_delete_existent_schedule()
    {
        $exam = ExamFactory::create($this->entityManager);
        $schedule = ScheduleFactory::add();
        $schedule->setExam($exam);
        $this->entityManager->persist($schedule);
        $this->entityManager->flush();

        $crawler = $this->delete(null, $this->api_url . $schedule->getId());
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('objects', $response);
    }

    public function test_delete_unexistent_schedule()
    {
        $crawler = $this->delete(null, $this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }
}
