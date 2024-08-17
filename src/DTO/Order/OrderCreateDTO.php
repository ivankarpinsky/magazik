<?php

namespace App\DTO\Order;

use Symfony\Component\Validator\Constraints as Assert;

class OrderCreateDTO {
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public readonly string $phone,

        #[Assert\NotBlank]
        #[Assert\GreaterThanOrEqual(0)]
        public readonly int $deliveryTypeId,
    ) {
    }
}