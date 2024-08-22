<?php

namespace App\Tests\Functional\Product;

use App\Entity\Product;
use App\Tests\Functional\AuthTestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateProductTest extends AuthTestCase {
    public function test_create_product_request_result(): void {
        ['user' => $user, 'token' => $token] = $this->createUserAndGetToken();
        $productName = $this->faker->word();
        $productPrice = $this->faker->numberBetween(1000, 10000);

        $this->client->request(Request::METHOD_POST, '/product/', [
            'name' => $productName,
            'price' => $productPrice,
        ], [], [
            'HTTP_Authorization' => sprintf('Bearer %s', $token), // Добавление токена в заголовок
        ]);

        $this->assertResponseIsSuccessful();

        $jsonResult = json_decode($this->client->getResponse()->getContent(), true);
        $productId = $jsonResult['id'];

        $entityManager = static::getContainer()->get('doctrine')->getManager();

        $productRepository = $entityManager->getRepository(Product::class);
        $savedProduct = $productRepository->findOneBy(['name' => $productName, 'price' => $productPrice]);

        $this->assertNotNull($savedProduct, 'Продукт не найден в базе данных');
        $this->assertEquals($savedProduct->getId(), $productId);
    }
}
