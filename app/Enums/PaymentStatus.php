<?php

namespace App\Enums;

class PaymentStatus
{
    const UNPAID = 'unpaid';
    const PAID = 'paid';
    const REFUNDED = 'refunded';

    public static function label($value): string
    {
        switch ($value) {
            case self::UNPAID:
                return 'Chưa thanh toán';
            case self::PAID:
                return 'Đã thanh toán';
            case self::REFUNDED:
                return 'Hoàn tiền';
            default:
                return $value;
        }
    }

    public static function values(): array
    {
        return [
            self::UNPAID,
            self::PAID,
            self::REFUNDED,
        ];
    }

    public static function options(): array
    {
        return [
            self::UNPAID => self::label(self::UNPAID),
            self::PAID => self::label(self::PAID),
            self::REFUNDED => self::label(self::REFUNDED),
        ];
    }
}
