<?php

namespace App\DTO\Order;

use Symfony\Component\Validator\Constraints as Assert;

class OrderUpdateStatusDTO {
    public function __construct(
        #[Assert\NotBlank]
        public readonly string $statusSlug,
    ) {
    }
}