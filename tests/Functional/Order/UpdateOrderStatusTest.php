<?php

namespace App\Tests\Functional\Order;

use App\Entity\Order;
use App\Entity\OrderDeliveryType;
use App\Enums\OrderStatusEnum;
use App\Enums\RoleEnum;
use App\Factory\CartFactory;
use App\Factory\CartItemFactory;
use App\Factory\OrderFactory;
use App\Factory\ProductFactory;
use App\Tests\Functional\AuthTestCase;
use Symfony\Component\HttpFoundation\Request;

class UpdateOrderStatusTest extends AuthTestCase {

    public function test_create_order_request_result(): void {
        $orderDeliveryTypeRepository = $this->em->getRepository(OrderDeliveryType::class);
        $orderRepository = $this->em->getRepository(Order::class);
        ['user' => $user, 'token' => $token] = $this->createUserAndGetToken(RoleEnum::ADMIN);
        $userId = $user->getId();
        $roles = $user->getRoles();
        dump($roles);
//        $product = ProductFactory::createOne();
//        $cart = CartFactory::createOne(['user' => $user]);
//        $cartItem = CartItemFactory::createOne(['cart' => $cart, 'product' => $product]);
        $order = OrderFactory::createOne();
        $this->client->request(Request::METHOD_POST, sprintf('/order/%d/status/update', $order->getId()), [
            'statusSlug' => OrderStatusEnum::CANCELLED->value,
        ], [], [
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
        ]);

        $this->assertResponseIsSuccessful();

        $jsonResult = json_decode($this->client->getResponse()
                                               ->getContent(), true);
        $orderId = $jsonResult['id'];
        $updatedOrder = $orderRepository->findOneBy([
            'id' => $orderId,
        ]);

        $this->assertEquals($updatedOrder->getStatus()->getSlug(), OrderStatusEnum::CANCELLED);

    }

}
