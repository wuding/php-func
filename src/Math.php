<?php

namespace Func;

class Math
{
    public static function numberDecimal($number, $frombase, $tobase)
    {
        $subject = $number;
        $patterns = array(
            2 => "/^([0-1]+)$/",
            10 => "/^([0-9]+)$/",
            16 => "/^([0-9A-F]+)$/i",
        );
        $pattern = $patterns[$frombase];
        $result = array(null, $subject);
        if (preg_match($pattern, $subject, $matches)) {
            $result = static::convertible($subject, $frombase, $tobase);
        }
        return $result;
    }

    public static function convertible($number, $frombase, $tobase)
    {
        $integer = base_convert($number, $frombase, $tobase);
        $digit = base_convert($integer, $tobase, $frombase);
        $result = array($number === $digit ? $frombase : false, $number);
        return $result;
    }

    public static function numberToBase($decimal)
    {
        $decimalize = array(2, 10, 16);
        $key = array_search($decimal, $decimalize);
        $increase = $key + 1;
        $index = 2 < $increase ? 0 : $increase;
        $tobase = $decimalize[$index];
        return $tobase;
    }
}
