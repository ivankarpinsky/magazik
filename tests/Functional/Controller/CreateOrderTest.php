<?php

namespace App\Tests\Functional\Controller;

use Faker\Factory;
use Faker\Generator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateOrderTest extends WebTestCase {

    private Generator $faker;

    public function setUp(): void {
        parent::setUp();

        $this->faker = Factory::create();
    }

    public function test_create_order_request_result(): void {
//        $client = static::createClient();
//        $client->request(Request::METHOD_POST, '/order/', [
//            'phone' => $this->faker->phoneNumber(),
//            'delivery_type_id' =>1,
//        ]);
//
//        //new OrderFactory();
//
//        $this->assertResponseIsSuccessful();
//        $jsonResult = json_decode($client->getResponse(), true);
//        $this->assertNotEquals($jsonResult['status'], 'ok');
    }
}
