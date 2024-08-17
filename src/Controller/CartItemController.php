<?php

namespace App\Controller;

use App\DTO\CartItem\CartItemRequestCreateDTO;
use App\DTO\CartItem\CartItemRequestRemoveDTO;
use App\DTO\CartItem\CartItemRequestUpdateQuantityDTO;
use App\Entity\Product;
use App\Entity\User;
use App\Enums\RoleEnum;
use App\Services\CartItemService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart/item', name: 'cart_item_')]
class CartItemController extends AbstractController {
    public function __construct(private CartItemService $cartItemService,
        private Security $security) {
    }

    #[Route('/', name: 'store', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function store(#[MapRequestPayload] CartItemRequestCreateDTO $cartItemRequestCreateDTO): JsonResponse {
        $user = $this->security->getUser();
        $cartItem = $this->cartItemService->create($cartItemRequestCreateDTO, $user);

        return $this->json(['id' => $cartItem->getId()], Response::HTTP_CREATED);
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
