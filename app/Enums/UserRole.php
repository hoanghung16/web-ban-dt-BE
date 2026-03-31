<?php

namespace App\Enums;

class UserRole
{
    const ADMIN = 'admin';
    const CUSTOMER = 'customer';

    public static function label($value): string
    {
        switch ($value) {
            case self::ADMIN:
                return 'Quản trị viên';
            case self::CUSTOMER:
                return 'Khách hàng';
            default:
                return $value;
        }
    }

    public static function values(): array
    {
        return [
            self::ADMIN,
            self::CUSTOMER,
        ];
    }

    public static function options(): array
    {
        return [
            self::ADMIN => self::label(self::ADMIN),
            self::CUSTOMER => self::label(self::CUSTOMER),
        ];
    }

    public static function isAdmin($value): bool
    {
        return $value === self::ADMIN;
    }

    public static function isCustomer($value): bool
    {
        return $value === self::CUSTOMER;
    }
}
