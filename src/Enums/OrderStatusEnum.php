<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OrderStatusEnum: string {
    use EnumTrait;

    case NOT_PAID = "requires_payment";
    case PAID = "success_payment";
    case ASSEMBLY = "in_assembly";
    case DELIVERY = "in_delivery";
    case PICKUP = "ready_for_pickup";
    case COMPLETED = "completed";
    case CANCELLED = "cancelled";
}