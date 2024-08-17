<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum OrderDeliveryTypeEnum: string {
    use EnumTrait;

    case COURIER = "courier";
    case PICKUP = "pickup";
}