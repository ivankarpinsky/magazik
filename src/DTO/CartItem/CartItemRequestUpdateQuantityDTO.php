<?php

namespace App\DTO\CartItem;

use App\Validators\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class CartItemRequestUpdateQuantityDTO {
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[EntityExists(entity: "App\Entity\CartItem")]
        public readonly int $cartItemId,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\GreaterThan(0)]
        public readonly int $quantity,
    ) {
    }
}