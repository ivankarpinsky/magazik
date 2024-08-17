<?php

namespace App\Controller;

use App\DTO\CartItem\CartItemRequestCreateDTO;
use App\DTO\CartItem\CartItemRequestRemoveDTO;
use App\DTO\CartItem\CartItemRequestUpdateQuantityDTO;
use App\DTO\Order\OrderCreateDTO;
use App\Entity\Product;
use App\Entity\User;
use App\Enums\RoleEnum;
use App\Services\CartItemService;
use App\Services\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order', name: 'order_')]
class OrderController extends AbstractController {
    public function __construct(private OrderService $orderService,
        private Security $security) {
    }

    #[Route('/', name: 'store', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function store(#[MapRequestPayload] OrderCreateDTO $orderCreateDTO): JsonResponse {
        $user = $this->security->getUser();
        $order = $this->orderService->create($orderCreateDTO, $user);

        return $this->json(['id' => $order->getId()], Response::HTTP_CREATED);
    }

    #[Route('/updateQuantity', name: 'updateQuantity', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function updateQuantity(#[MapRequestPayload] CartItemRequestUpdateQuantityDTO $requestDTO): JsonResponse {
        $user = $this->security->getUser();
        $cartItem = $this->cartItemService->updateQuantity($requestDTO, $user);

        return $this->json(['id' => $cartItem->getId()], Response::HTTP_OK);
    }

    #[Route('/', name: 'remove', methods: ['DELETE'])]
    #[IsGranted('ROLE_USER')]
    public function remove(#[MapRequestPayload] CartItemRequestRemoveDTO $requestDTO): JsonResponse {
        $user = $this->security->getUser();
        $this->cartItemService->remove($requestDTO, $user);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
