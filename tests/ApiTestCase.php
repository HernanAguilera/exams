<?php

namespace App\Tests;

use App\Entity\User;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase {

    protected $entityManager;
    protected $client;
    protected $api_url;
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
        $this->client = $this->createAuthenticatedClient($this->faker->email(), $this->faker->word());
        // $this->entityManager  = static::getContainer()->get('doctrine')->getManager();
        $this->entityManager  = $this->client->getContainer()->get('doctrine')->getManager();
        $this->api_url = '/api/';
    }

    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    protected function createAuthenticatedClient($username, $password)
    {
        $client = static::createClient();
        $entityManager  = $client->getContainer()->get('doctrine')->getManager();
        $user = $entityManager->getRepository(User::class)->createUser($username, $password);
        
        $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );
        $data = json_decode($client->getResponse()->getContent(), true);

        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    protected function request($method, $data, $url = null, $params = []) {
        $url = $url ?? $this->api_url;
        if (!in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']))
            return null;
        return $this->client->request(
            $method,
            $url,
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );
    }

    public function get($url = null, $params = []) {
        $url = $url ?? $this->api_url;
        return $this->client->request('GET', $url);
    }

    public function post($data, $url = null, $params = []) {
        $this->request('POST', $data, $url, $params);
    }

    public function put($data, $url = null, $params = []) {
        $this->request('PUT', $data, $url, $params);
    }

    public function patch($data, $url = null, $params = []) {
        $this->request('PATCH', $data, $url, $params);
    }

    public function delete($data, $url = null, $params = []) {
        $this->request('DELETE', $data, $url, $params);
    }

    public function getResponse() {
        return json_decode($this->client->getResponse()->getContent(), true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
