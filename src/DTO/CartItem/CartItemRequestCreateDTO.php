<?php

namespace App\DTO\CartItem;

use App\Validators\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class CartItemRequestCreateDTO {
    public function __construct(

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[EntityExists(entity: "App\Entity\Product")]
        public readonly int $productId,

        #[Assert\NotBlank]
        #[Assert\Type(type: 'integer')]
        #[Assert\GreaterThan(0)]
        public readonly int $quantity,
    ) {
    }
}