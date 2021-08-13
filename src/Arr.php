<?php

namespace Func;

class Arr
{
    const VERSION = '21.8.13';

    // null 合并运算符
    public static function key_null($arr, $key, $value = null)
    {
        $val = $arr[$key] ?? $value;
        return $val;
    }

    // HTTP 头信息
    public static function headers($variable, $ignore_empty = null)
    {
        $pieces = array();
        foreach ($variable as $key => $value) {
            if ($ignore_empty && !$value) {
                continue 1;
            }
            $pieces[] = is_numeric($key) ? $value : "$key: $value";
        }
        return $pieces;
    }
}

