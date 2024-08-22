<?php

namespace App\Services;

use App\DTO\Order\OrderCreateDTO;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Order;
use App\Entity\OrderDeliveryType;
use App\Entity\OrderItem;
use App\Entity\OrderStatus;
use App\Entity\User;
use App\Enums\OrderStatusEnum;
use App\Repository\CartItemRepository;
use App\Repository\CartRepository;
use App\Repository\OrderDeliveryTypeRepository;
use App\Repository\OrderItemRepository;
use App\Repository\OrderRepository;
use App\Repository\OrderStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderService {

    public function __construct(private EntityManagerInterface $entityManager,
        private CartRepository $cartRepository,
        private OrderRepository $orderRepository,
        private OrderDeliveryTypeRepository $orderDeliveryTypeRepository,
        private OrderStatusRepository $orderStatusRepository,) {
    }

    public function getList(UserInterface $user): array {
        return $this->orderRepository->findBy(['user' => $user]);
    }

    public function create(OrderCreateDTO $orderCreateDTO, UserInterface $user): Order {
        $order = new Order();

        $cart = $this->cartRepository->findOneBy(['user' => $user]);
        $cartItems = $cart->getCartItems();
        $newOrderStatus = $this->orderStatusRepository->findOneBy(['slug' => OrderStatusEnum::NOT_PAID]);
        $deliveryType = $this->orderDeliveryTypeRepository->find($orderCreateDTO->deliveryTypeId);

        $order->setPhone($orderCreateDTO->phone);
        $order->setDeliveryType($deliveryType);
        $order->setStatus($newOrderStatus);
        $order->setUser($user);

        $orderAmount = 0;
        $quantityTotal = array_sum($cartItems->map((fn($item) => $item->getQuantity()))
                                             ->toArray());

        if ($quantityTotal > 20) {
            throw new BadRequestHttpException('The total quantity exceeds the allowed limit of 20.');
        }

        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItem();
            $product = $cartItem->getProduct();
            $quantity = $cartItem->getQuantity();
            $price = $product->getPrice();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setPrice($price);
            $orderItem->setAmount($quantity * $price);
            $orderAmount += $quantity * $price;
            $order->addOrderItem($orderItem);

            $this->entityManager->persist($orderItem);
        }

        $order->setAmount($orderAmount);
        $order->setCreatedAtNow();

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function updateStatus(int $orderId, string $statusSlug,
        ?UserInterface $user): ?object {
        if (!in_array($statusSlug, OrderStatusEnum::values())) {
            throw new UnprocessableEntityHttpException("Wrong status slug");
        }

        $status = $this->orderStatusRepository->findOneBy(['slug' => $statusSlug]);

        $order = $this->orderRepository->find($orderId);
        $order->setStatus($status);

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }
}