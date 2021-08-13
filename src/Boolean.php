<?php

namespace Func;

class Boolean
{
    public function __construct()
    {
        print_r([__FILE__, __LINE__]);
    }

    public static function versusCompare($number, $max, $min)
    {
        if ($max > $number && $number > $min) {
            return true;
        }
        return false;
    }
}
