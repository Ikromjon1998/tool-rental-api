<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case CUSTOMER = 'customer';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::CUSTOMER => 'Customer',
            self::ADMIN => 'Admin',
        };
    }
}
