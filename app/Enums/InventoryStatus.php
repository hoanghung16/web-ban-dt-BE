<?php

namespace App\Enums;

class InventoryStatus
{
    const IN_STOCK = 'in_stock';
    const LOW_STOCK = 'low_stock';
    const OUT_OF_STOCK = 'out_of_stock';

    public static function label($value): string
    {
        switch ($value) {
            case self::IN_STOCK:
                return 'Còn hàng';
            case self::LOW_STOCK:
                return 'Sắp hết hàng';
            case self::OUT_OF_STOCK:
                return 'Hết hàng';
            default:
                return $value;
        }
    }

    public static function values(): array
    {
        return [
            self::IN_STOCK,
            self::LOW_STOCK,
            self::OUT_OF_STOCK,
        ];
    }

    public static function options(): array
    {
        return [
            self::IN_STOCK => self::label(self::IN_STOCK),
            self::LOW_STOCK => self::label(self::LOW_STOCK),
            self::OUT_OF_STOCK => self::label(self::OUT_OF_STOCK),
        ];
    }

    public static function fromQuantity(int $quantity): string
    {
        if ($quantity > 10) {
            return self::IN_STOCK;
        } elseif ($quantity > 0) {
            return self::LOW_STOCK;
        } else {
            return self::OUT_OF_STOCK;
        }
    }
}
