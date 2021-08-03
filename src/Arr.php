<?php

namespace Func;

class Arr
{
    // null 合并运算符
    public static function key_null($arr, $key, $value = null)
    {
        $val = $arr[$key] ?? $value;
        return $val;
    }
}

