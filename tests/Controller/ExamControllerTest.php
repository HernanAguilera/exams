<?php

namespace App\Tests\Controller;

use App\EntityFactories\ExamFactory;
use App\EntityFactories\CompanyFactory;
use App\Entity\Exam;
use App\Tests\ApiTestCase;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class ExamControllerTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->api_url = '/api/exam/';
    }

    public function test_list_respond_correctly(): void
    {
        $n = rand(1, 20);
        $company = CompanyFactory::create($this->entityManager);
        $exams = ExamFactory::create($this->entityManager, $n);
        if (!is_array($exams)) {
            $exams = [$exams];
        }
        foreach($exams as $exam) {
            $exam->addCompany($company);
            $this->entityManager->persist($exam);
        }
        $this->entityManager->flush();

        $crawler = $this->get();

        $response = $this->getResponse();

        $this->assertEquals($n, count($response));
        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('companies', $response[0]);
        $this->assertEquals(count($exams[0]->getCompanies()), count($response[0]['companies']));
    }

    public function test_list_filter_respond_correctly(): void
    {
        $n = 5;
        $company = CompanyFactory::create($this->entityManager);
        $exams = ExamFactory::create($this->entityManager, $n);
        foreach($exams as $exam) {
            $exam->addCompany($company);
            $this->entityManager->persist($exam);
        }
        $this->entityManager->flush();

        $name = $exams[0]->getName();
        $company = $company->getCommercialName();

        $name = substr($name, 0, strlen($name)-2);
        $company = substr($company, 0, strlen($company)-2);

        $crawler = $this->get("{$this->api_url}?name={$name}&company={$company}");

        $response = $this->getResponse();

        // dd($response);

        $this->assertArrayHasKey('name', $response[0]);
        $this->assertArrayHasKey('companies', $response[0]);
        $this->assertEquals(count($exams[0]->getCompanies()), count($response[0]['companies']));
    }

    public function test_create_a_exam_with_correct_data()
    {
        $company = CompanyFactory::create($this->entityManager);
        $data = [
            'name' => $this->faker->text(255),
            'companies' => [$company->getId()]
        ];
        $crawler = $this->post($data);
        $response = $this->getResponse();

        $this->assertArrayHasKey('object', $response);
        $this->assertArrayHasKey('companies', $response['object']);
        $this->assertEquals($data['name'], $response['object']['name']);
        $this->assertEquals($data['companies'][0], $response['object']['companies'][0]['id']);
    }

    public function test_trying_create_exam_with_missing_data()
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
        $company = CompanyFactory::create($this->entityManager);
        $exam = ExamFactory::create($this->entityManager);
        $exam->addCompany($company);
        $this->entityManager->persist($exam);
        $this->entityManager->flush();

        $crawler = $this->get($this->api_url . $exam->getId());
        $response = $this->getResponse();

        print_r($response);

        $this->assertEquals($exam->getId(), $response['id']);
        $this->assertEquals($exam->getName(), $response['name']);
        $this->assertArrayHasKey('companies', $response);
        $this->assertEquals($exam->getCompanies()[0]->getId(), $response['companies'][0]['id']);
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
        $exam = ExamFactory::create($this->entityManager);
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
        $exam = ExamFactory::create($this->entityManager);
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
}
