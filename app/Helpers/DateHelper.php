<?php

namespace App\Helpers;

use DateTime;

class DateHelper
{
    /**
     * Format date to Vietnamese format
     * Input: "2026-03-31" → Output: "31/03/2026"
     */
    public static function format($date, $format = 'd/m/Y'): string
    {
        if (is_null($date)) {
            return '';
        }

        if (is_string($date)) {
            $date = new DateTime($date);
        }

        return $date->format($format);
    }

    /**
     * Format date and time to Vietnamese format
     * Input: "2026-03-31 14:30:00" → Output: "31/03/2026 14:30"
     */
    public static function formatDateTime($date, $format = 'd/m/Y H:i'): string
    {
        if (is_null($date)) {
            return '';
        }

        if (is_string($date)) {
            $date = new DateTime($date);
        }

        return $date->format($format);
    }

    /**
     * Format time only
     * Input: "2026-03-31 14:30:00" → Output: "14:30"
     */
    public static function formatTime($date, $format = 'H:i'): string
    {
        if (is_null($date)) {
            return '';
        }

        if (is_string($date)) {
            $date = new DateTime($date);
        }

        return $date->format($format);
    }

    /**
     * Get human-readable relative time
     * Input: yesterday → Output: "1 ngày trước"
     */
    public static function humanDiff($date): string
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }

        $now = new DateTime();
        $diff = $now->diff($date);

        if ($diff->days === 0) {
            if ($diff->h === 0) {
                return $diff->i . ' phút trước';
            }
            return $diff->h . ' giờ trước';
        }

        if ($diff->days === 1) {
            return 'Hôm qua';
        }

        return $diff->days . ' ngày trước';
    }

    /**
     * Check if date is today
     */
    public static function isToday($date): bool
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }

        return $date->format('Y-m-d') === (new DateTime())->format('Y-m-d');
    }

    /**
     * Check if date is yesterday
     */
    public static function isYesterday($date): bool
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }

        $yesterday = (new DateTime())->modify('-1 day');
        return $date->format('Y-m-d') === $yesterday->format('Y-m-d');
    }

    /**
     * Check if date is in the past
     */
    public static function isPast($date): bool
    {
        if (is_string($date)) {
            $date = new DateTime($date);
        }

        return $date < new DateTime();
    }
}
