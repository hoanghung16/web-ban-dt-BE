<?php

namespace App\Enums;

class OrderStatus
{
    const PENDING = 'pending';
    const CONFIRMED = 'confirmed';
    const PROCESSING = 'processing';
    const SHIPPED = 'shipped';
    const DELIVERED = 'delivered';
    const CANCELLED = 'cancelled';

    public static function label($value): string
    {
        switch ($value) {
            case self::PENDING:
                return 'Chờ xử lý';
            case self::CONFIRMED:
                return 'Đã xác nhận';
            case self::PROCESSING:
                return 'Đang xử lý';
            case self::SHIPPED:
                return 'Đã gửi';
            case self::DELIVERED:
                return 'Đã giao';
            case self::CANCELLED:
                return 'Đã hủy';
            default:
                return $value;
        }
    }

    public static function values(): array
    {
        return [
            self::PENDING,
            self::CONFIRMED,
            self::PROCESSING,
            self::SHIPPED,
            self::DELIVERED,
            self::CANCELLED,
        ];
    }

    public static function options(): array
    {
        return [
            self::PENDING => 'Chờ xử lý',
            self::CONFIRMED => 'Đã xác nhận',
            self::PROCESSING => 'Đang xử lý',
            self::SHIPPED => 'Đã gửi',
            self::DELIVERED => 'Đã giao',
            self::CANCELLED => 'Đã hủy',
        ];
    }
}
