<?php

namespace App\Helpers;

class ValidationHelper
{
    /**
     * Check if email is valid
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check if phone number is valid (Vietnamese)
     */
    public static function isValidPhone(string $phone): bool
    {
        // Remove all non-digits
        $cleaned = preg_replace('/[^\d]/', '', $phone);

        // Vietnamese phone numbers are 10 digits
        return strlen($cleaned) === 10;
    }

    /**
     * Check if URL is valid
     */
    public static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Validate price (must be numeric and positive)
     */
    public static function isValidPrice($price): bool
    {
        return is_numeric($price) && (float)$price >= 0;
    }

    /**
     * Validate quantity (must be positive integer)
     */
    public static function isValidQuantity($quantity): bool
    {
        return is_numeric($quantity) && (int)$quantity >= 0;
    }

    /**
     * Truncate text to specified length
     */
    public static function truncate(string $text, int $length = 50, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Generate slug from text
     * "The King Store" → "the-king-store"
     */
    public static function slug(string $text): string
    {
        $text = strtolower($text);
        $text = preg_replace('/[^\w\s-]/', '', $text);
        $text = preg_replace('/[\s_-]+/', '-', $text);
        $text = trim($text, '-');
        return $text;
    }

    /**
     * Check if string contains only letters and numbers
     */
    public static function isAlphanumeric(string $text): bool
    {
        return preg_match('/^[a-zA-Z0-9_-]+$/', $text) === 1;
    }
}
