<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum RoleEnum: string {
    use EnumTrait;

    case ADMIN = "ROLE_ADMIN";
    case USER = "ROLE_USER";
}