<?php

namespace taguz91\CommonHelpers;

class RegexHelpers
{

    /**
     * Check if string contains letters 
     */
    static function containsLetters(string $value)
    {
        return preg_match('/[A-Za-z]/i', $value) != 0;
    }
}
