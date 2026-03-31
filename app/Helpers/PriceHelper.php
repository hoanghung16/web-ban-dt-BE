<?php

namespace App\Helpers;

class PriceHelper
{
    /**
     * Format price with dot separator and đ symbol
     * Input: 1500000 → Output: "1.500.000đ"
     */
    public static function format($price): string
    {
        if (is_null($price)) {
            return '0đ';
        }
        return number_format((int)$price, 0, ',', '.') . 'đ';
    }

    /**
     * Parse price string to number
     * Input: "1.500.000đ" → Output: 1500000
     */
    public static function parse($priceString): int
    {
        if (is_null($priceString)) {
            return 0;
        }
        return (int)preg_replace('/[^\d]/', '', (string)$priceString);
    }

    /**
     * Calculate discount amount
     */
    public static function discountAmount($originalPrice, $salePrice): float
    {
        $original = (float)$originalPrice;
        $sale = (float)$salePrice;
        return max(0, $original - $sale);
    }

    /**
     * Calculate discount percentage
     */
    public static function discountPercent($originalPrice, $salePrice): int
    {
        $original = (float)$originalPrice;
        $sale = (float)$salePrice;

        if ($original == 0) {
            return 0;
        }

        return (int)round(($original - $sale) / $original * 100);
    }

    /**
     * Get effective price (sale price if available, otherwise original)
     */
    public static function effectivePrice($originalPrice, $salePrice = null): float
    {
        if (!is_null($salePrice) && (float)$salePrice > 0) {
            return (float)$salePrice;
        }
        return (float)$originalPrice;
    }

    /**
     * Calculate total for items with quantity
     */
    public static function calculateTotal(float $price, int $quantity): float
    {
        return $price * $quantity;
    }
}
