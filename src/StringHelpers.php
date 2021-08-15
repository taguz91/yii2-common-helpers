<?php

namespace taguz91\CommonHelpers;

class StringHelpers
{

    /**
     * Repet a value if multiple is minus to 0 not repet nothing
     */
    static function repet(string $value, int $multiple)
    {
        return str_repeat($value, $multiple > 0 ? $multiple : 0);
    }

    /**
     * String contains a value
     */
    static function contains(string $value, string $needed)
    {
        return strpos($value, $needed) !== false;
    }
}
