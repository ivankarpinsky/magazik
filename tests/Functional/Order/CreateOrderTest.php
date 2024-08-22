<?php

namespace App\Tests\Functional\Order;

use App\Entity\Order;
use App\Entity\OrderDeliveryType;
use App\Entity\Product;
use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Factory\ProductFactory;
use App\Repository\OrderDeliveryTypeRepository;
use App\Repository\OrderRepository;
use App\Tests\Functional\AuthTestCase;
use Symfony\Component\HttpFoundation\Request;

class CreateOrderTest extends AuthTestCase {
//    public function __construct(private OrderDeliveryTypeRepository $orderDeliveryTypeRepository,
//        private OrderRepository $orderRepository,) {
//        //parent::__construct();
//    }

    public function test_create_order_request_result(): void {
        $orderDeliveryTypeRepository = $this->em->getRepository(OrderDeliveryType::class);
        $orderRepository = $this->em->getRepository(Order::class);
        ['user' => $user, 'token' => $token] = $this->createUserAndGetToken();
        $userId = $user->getId();
        $product = ProductFactory::createOne();
        $cart = CartFactory::createOne(['user' => $user]);
        $cartItem = CartItemFactory::createOne(['cart' => $cart, 'product' => $product]);

        $deliveryType = $orderDeliveryTypeRepository->findOneBy([]);
        $phone = $this->faker->phoneNumber();
        $this->client->request(Request::METHOD_POST, '/order/', [
            'phone' => $phone,
            'deliveryTypeId' => $deliveryType->getId(),
        ], [], [
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ]);

        $this->assertResponseIsSuccessful();

        $jsonResult = json_decode($this->client->getResponse()
                                               ->getContent(), true);
        $orderId = $jsonResult['id'];

        $savedOrder = $orderRepository->findOneBy([
            'id' => $orderId,
            'deliveryType' => $deliveryType,
            'phone' => $phone,
            'user' => $userId
        ]);

        $this->assertNotNull($savedOrder, 'Продукт не найден в базе данных');
        $orderItems = $savedOrder->getOrderItems();
        foreach ($orderItems as $orderItem) {
            $this->assertEquals($orderItem->getProduct()->getId(), $product->getId());
            $this->assertEquals($orderItem->getPrice(), $product->getPrice());
        }
    }

    public function test_order_quantity_limit(): void {
        $orderDeliveryTypeRepository = $this->em->getRepository(OrderDeliveryType::class);
        $orderRepository = $this->em->getRepository(Order::class);
        ['user' => $user, 'token' => $token] = $this->createUserAndGetToken();
        $userId = $user->getId();
        $cart = CartFactory::createOne(['user' => $user]);
        $product = ProductFactory::createOne();
        $cartItem = CartItemFactory::createOne(['cart' => $cart, 'product' => $product, 'quantity' => 7]);
        $product = ProductFactory::createOne();
        $cartItem = CartItemFactory::createOne(['cart' => $cart, 'product' => $product, 'quantity' => 7]);
        $product = ProductFactory::createOne();
        $cartItem = CartItemFactory::createOne(['cart' => $cart, 'product' => $product, 'quantity' => 7]);

        $deliveryType = $orderDeliveryTypeRepository->findOneBy([]);
        $phone = $this->faker->phoneNumber();
        $this->client->request(Request::METHOD_POST, '/order/', [
            'phone' => $phone,
            'deliveryTypeId' => $deliveryType->getId(),
        ], [], [
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ]);

        $this->assertResponseStatusCodeSame(400);
    }
}
