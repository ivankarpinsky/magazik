<?php

namespace App\Tests\Functional;

use App\Factory\UserFactory;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AuthTestCase extends WebTestCase {
    protected Generator $faker;
    protected KernelBrowser $client;
    protected ?EntityManagerInterface $em;

    protected function setUp(): void {
        parent::setUp();
//        $kernel = self::bootKernel();
        $this->faker = Factory::create();
        $this->client = static::createClient();
        $this->em = $this->client->getContainer()
                           ->get('doctrine')
                           ->getManager();
    }

    protected function createUserAndGetToken($userRoleSlug = 'ROLE_USER'): array
    {
        $password = $this->faker->password();
        $user = UserFactory::new()->withRole($userRoleSlug)->createOne(['password' => $password]);
        $this->client->request(
            Request::METHOD_GET, // Для авторизации POST запрос
            '/auth/login',
            [],
            [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'HTTP_ACCEPT_LANGUAGE' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
                'HTTP_ORIGIN' => 'https://sa.team-dev.ru',
                'HTTP_PRIORITY' => 'u=1, i',
                'HTTP_SEC_CH_UA' => '"Google Chrome";v="125", "Chromium";v="125", "Not.A/Brand";v="24"',
                'HTTP_SEC_CH_UA_MOBILE' => '?0',
                'HTTP_SEC_CH_UA_PLATFORM' => '"Windows"',
                'HTTP_SEC_FETCH_DEST' => 'empty',
                'HTTP_SEC_FETCH_MODE' => 'cors',
                'HTTP_SEC_FETCH_SITE' => 'same-site',
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
                'HTTP_CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'username' => $user->getEmail(),
                'password' => $password,
            ])
        );
        $roles = $user->getRoles();
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        return ['user' => $user, 'token' => $data['token']];
    }
}