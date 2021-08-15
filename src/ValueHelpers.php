<?php

namespace taguz91\CommonHelpers;

class ValueHelpers
{

    static function toDotDecimals(
        $value,
        int $decimals = 2,
        string $separator = ''
    ): string {
        return number_format($value, $decimals, '.', $separator);
    }

    static function toCommaDecimals($value, int $decimals = 2)
    {
        return number_format($value, $decimals, ',', '');
    }

    /**
     * Format a number value with decimals if is needed
     * ```
     * 100.1 => 100.10
     * 100 => 100
     * ```
     */
    static function numberFormat(
        $value,
        int $decimals = 2,
        string $separator = ',' | '.',
        bool $checkDecimals = false
    ) {
        if ($checkDecimals && !self::isDecimal($value)) {
            $decimals = 0;
        }
        return number_format($value, $decimals, $separator, '');
    }

    /**
     * Round number with decumal precision
     */
    static function round(float $value, int $precision = 2): float
    {
        return round($value, $precision);
    }

    /**
     * Round the number to minus value
     */
    static function floorFormat(
        float $value,
        int $decimals = 2,
        string $separator = '.'
    ) {
        return self::numberFormat(
            floor($value),
            $decimals,
            $separator
        );
    }

    static function floatFormat(
        float $value,
        bool $checkDecimals = false
    ) {
        $decimals = 2;
        if ($checkDecimals && !self::isDecimal($value)) {
            $decimals = 0;
        }

        return number_format($value, $decimals);
    }

    static function isDecimal($val): bool
    {
        return is_numeric($val) && floor($val) != $val;
    }

    /**
     * Create a number from a string
     * ```
     * 1,000.01 => 1000.01
     * ```
     */
    static function strintToFloat(string $number): float
    {
        return floatval(preg_replace("/[^-0-9\.]/", "", $number));
    }

    /**
     * Have 0 in the first index
     */
    static function isBeforeZero($value)
    {
        if (empty($value) || $value == '0') {
            return false;
        }
        if (is_numeric($value) && (is_string($value) && strpos($value, '.') !== false)) {
            return false;
        }
        return (is_string($value) && $value[0] === '0');
    }
}
