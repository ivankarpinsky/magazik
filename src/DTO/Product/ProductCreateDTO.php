<?php

namespace App\DTO\Product;

use Symfony\Component\Validator\Constraints as Assert;

class ProductCreateDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public readonly string $name,

        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(0)]
        public readonly int $price,
    ) {
    }
}