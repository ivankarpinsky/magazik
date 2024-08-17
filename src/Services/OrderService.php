<?php

namespace App\Services;

use App\DTO\Order\OrderCreateDTO;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Entity\User;
use App\Enums\OrderStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

class OrderService {

    public function __construct(private EntityManagerInterface $entityManager) {}

    public function create(OrderCreateDTO $orderCreateDTO, User $user): Order {
        $order = new Order();

        $cartRepository = $this->entityManager->getRepository(Cart::class);
        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $orderStatusRepository = $this->entityManager->getRepository(OrderStatus::class);
        $cart = $cartRepository->findOneBy(['user' => $user]);
        $cartItems = $cart->getCartItems();
        $newOrderStatus = $orderStatusRepository->findOneBy(['slug' => OrderStatusEnum::NOT_PAID]);

        $order->setPhone($orderCreateDTO->phone);
        $order->setDeliveryTypeId($orderCreateDTO->deliveryTypeId);
        $order->setStatusId($newOrderStatus->getId());

        foreach ($cartItems as $cartItem) {
            $order->addOrderItem($cartItem);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}