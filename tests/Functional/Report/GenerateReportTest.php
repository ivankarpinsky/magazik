<?php

namespace App\Tests\Functional\Report;

use App\Factory\OrderFactory;
use App\Factory\ProductFactory;
use App\Tests\Functional\AuthTestCase;
use Symfony\Component\HttpFoundation\Request;

class GenerateReportTest extends AuthTestCase {
    public function test_get_product_list_request_result(): void {
//        $products = ProductFactory::createMany(5);
//        $order = OrderFactory::createOne();
//
//        $products = ProductFactory::createMany(3);

        $this->client->request(Request::METHOD_POST, '/report/generate');
        $this->assertResponseIsSuccessful();

//        $dbProducts = json_decode($this->client->getResponse()->getContent(), true);
//        $productIds = array_map(fn ($product) => $product['id'], $dbProducts);
//
//        foreach ($products as $product) {
//            $this->assertContains($product->getId(), $productIds);
//        }
    }
}
