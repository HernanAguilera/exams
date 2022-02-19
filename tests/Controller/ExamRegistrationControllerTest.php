<?php

namespace App\Tests\Controller;

use App\EntityFactories\TestFactory;
use App\EntityFactories\ExamFactory;
use App\EntityFactories\CompanyFactory;
use App\Entity\User;
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
        $user = $this->createUser();

        $test->setExam($exam);
        $test->setUser($user);

        $this->entityManager->persist($test);
        $this->entityManager->flush();
        
        $crawler = $this->get();

        $response = $this->getResponse();

        $this->assertEquals($n, count($response));
        $this->assertEquals($test->getId(), $response['registers'][0]['id']);
        $this->assertEquals($exam->getId(), $response['registers'][0]['exam']['id']);
        $this->assertEquals($user->getId(), $response['registers'][0]['user']['id']);
    }

    public function test_create_a_exam_with_correct_data()
    {
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();

        $data = [
            'exam' => $exam->getId(),
            'user' => $user->getId(),
            'date' => date_format($this->faker->dateTimeBetween('+1 week', '+4 weeks'), 'Y-m-d')
        ];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(201);
        $this->assertArrayHasKey('id', $response);
        $this->assertEquals($data['exam'], $response['exam']['id']);
        $this->assertEquals($data['user'], $response['user']['id']);
        $this->assertEquals($data['date'], $response['date']);
    }

    public function test_trying_create_test_with_missing_data()
    {
        $data = [];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(400);
        $this->assertArrayHasKey('errors', $response);
        $this->assertArrayHasKey('name', $response['errors']);
    }

    public function test_get_individual_existent_exam()
    {
        $test = TestFactory::add();
        $exam = ExamFactory::create($this->entityManager);
        $user = $this->createUser();

        $test->setExam($exam);
        $test->setUser($user);

        $this->entityManager->persist($test);
        $this->entityManager->flush();

        $crawler = $this->get($this->api_url . $test->getId());
        $response = $this->getResponse();

        print_r($response);

        // $this->assertEquals($exam->getId(), $response['id']);
        // $this->assertEquals($exam->getName(), $response['name']);
        // $this->assertArrayHasKey('companies', $response);
        // $this->assertEquals($exam->getCompanies()[0]->getId(), $response['companies'][0]['id']);
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
        $company = CompanyFactory::create($this->entityManager);
        $exam = TestFactory::create($this->entityManager);
        $exam->addCompany($company);
        $this->entityManager->persist($exam);
        $this->entityManager->flush();

        $company2 = CompanyFactory::create($this->entityManager);
        $data = [
            'name' => $this->faker->text(255),
            'companies' => [$company2->getId()]
        ];

        $crawler = $this->put($data, $this->api_url . $exam->getId());
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertEquals($data['name'], $response['object']['name']);
        $this->assertEquals($data['companies'][0], $response['object']['companies'][1]['id']);
    }

    public function test_update_unexistent_exam_with_correct_data()
    {
        $data = [
            'name' => $this->faker->text(255)
        ];

        $crawler = $this->put($data, $this->api_url . $this->faker->randomNumber(5, false));
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(404);
        $this->assertNull($response);
    }

    public function test_delete_existent_exam()
    {
        $exam = TestFactory::create($this->entityManager);
        $crawler = $this->delete(null, $this->api_url . $exam->getId());
        $response = $this->getResponse();

        $this->assertResponseStatusCodeSame(200);
        $this->assertArrayHasKey('message', $response);
        $this->assertArrayHasKey('objects', $response);
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