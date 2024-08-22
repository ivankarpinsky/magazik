<?php

namespace App\Controller;

use App\DTO\CartItem\CartItemRequestCreateDTO;
use App\DTO\CartItem\CartItemRequestRemoveDTO;
use App\DTO\CartItem\CartItemRequestUpdateQuantityDTO;
use App\DTO\Order\OrderCreateDTO;
use App\DTO\Order\OrderUpdateStatusDTO;
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

        #[Route('/{orderId}/status/update', name: 'store', methods: ['POST'])]
        #[IsGranted('ROLE_ADMIN')]
        public function updateStatus(int $orderId, #[MapRequestPayload] OrderUpdateStatusDTO $orderUpdateStatusDTO): JsonResponse {
            $user = $this->security->getUser();
            $order = $this->orderService->updateStatus($orderId, $orderUpdateStatusDTO->statusSlug, $user);

            return $this->json(['id' => $order->getId()], Response::HTTP_OK);
        }

    #[Route('/list', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse {
        $user = $this->security->getUser();

        return $this->json($this->orderService->getList($user));
    }
}
