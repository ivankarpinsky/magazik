<?php

namespace App\Services;

use App\DTO\CartItem\CartItemRequestCreateDTO;
use App\DTO\CartItem\CartItemRequestRemoveDTO;
use App\DTO\CartItem\CartItemRequestUpdateQuantityDTO;
use App\Entity\Cart;
use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CartItemService {

    public function __construct(private EntityManagerInterface $entityManager) {
    }

    public function create(CartItemRequestCreateDTO $productCreateDTO,
        User $user): CartItem {
        $cartRepository = $this->entityManager->getRepository(Cart::class);
        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        $productRepository = $this->entityManager->getRepository(Product::class);
        $product = $productRepository->find($productCreateDTO->productId);
        $cartItem = $cartItemRepository->findOneBy(['product' => $product, 'cart' => $cart]);

        if (!$cartItem) {
            $cartItem = new CartItem();
            $cartItem->setProduct($product);
            $cartItem->setQuantity($productCreateDTO->quantity);
            $cart->addCartItem($cartItem);
        }

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();

        return $cartItem;
    }

    public function updateQuantity(CartItemRequestUpdateQuantityDTO $requestDTO,
        User $user): CartItem {
        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);

        $cartItem = $cartItemRepository->find($requestDTO->cartItemId);

        $this->checkUserAccess($cartItem, $user);

        $cartItem->setQuantity($requestDTO->quantity);

        $this->entityManager->persist($cartItem);
        $this->entityManager->flush();

        return $cartItem;
    }

    public function remove(CartItemRequestRemoveDTO $requestDTO,
        User $user): CartItem {
        $cartItemRepository = $this->entityManager->getRepository(CartItem::class);
        $cartItem = $cartItemRepository->find($requestDTO->cartItemId);

        $this->checkUserAccess($cartItem, $user);

        $this->entityManager->remove($cartItem);
        $this->entityManager->flush();

        return $cartItem;
    }

    private function checkUserAccess(CartItem $cartItem, User $user): void {
        if ($cartItem->getCart()->getUser()->getId() != $user->getId()) {
            throw new AccessDeniedException('You do not have permission to access this resource.');
        }
    }
}