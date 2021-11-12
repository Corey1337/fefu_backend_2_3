<?php

namespace App\Sanitizers;

class PhoneSanitizer
{
    public static function num_sanitize(?string $str)
    {
        if($str !== null)
        {
            $str = preg_replace('/\D+/', '', $str);
            $str[0] = '7';
        }
        return $str;
    }
}