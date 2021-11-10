<?php

namespace App\Sanitizers;

class PhoneSanitizer
{
    public static function num_sanitize(string $str)
    {
        $str = preg_replace('/\D+/', '', $str);
        $str[0] = '7';
        return $str;
    }
}