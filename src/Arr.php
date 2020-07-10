<?php

namespace Func;

class Arr
{
    public function __construct()
    {

    }

    public static function diff($arr = [], $other = [], $ignore = [], $null = false)
    {
        return array_diff_kv($arr, $other, $ignore, $null);
    }
}

/**
 * 比较数组的键值
 * @param  array $arr    要比较的数组
 * @param  array $other  对比数组
 * @param  array $ignore 忽略键名
 * @param  bool  $null   附上键或值为null的项
 * @return array         返回值不同的键名
 */
function array_diff_kv($arr = [], $other = [], $ignore = [], $null = false)
{
    foreach ($ignore as $row) {
        unset($arr[$row]);
    }

    $diff = [];
    foreach ($arr as $key => $value) {
        if (array_key_exists($key, $other)) {
            if ($value != $val = $other[$key]) {
                $diff[$key] = [$value, $val];
            }
        } elseif($null) {
            $diff[$key] = null;
        }
    }
    return $diff;
}

function arr_fixed_assoc(array &$array, $reset = false)
{
    $arr = $array;
    foreach ($arr as $key => $value) {
        if (is_numeric($key)) {
            unset($arr[$key]);
            $key = $value;
            $arr[$key] = $value;
        }
    }
    if ($reset) {
        $array = $arr;
    }
    return $arr;
}

function arr_reset_values(array &$array, $set = [], $reset = false)
{
    $prefix = isset($set['prefix']) ? $set['prefix'] : '';
    $suffix = isset($set['suffix']) ? $set['suffix'] : '';
    $arr = $array;
    foreach ($arr as $key => &$value) {
        if (is_string($key)) {
            $value = $prefix . $value . $suffix;
        }
    }
    if ($reset) {
        $array = $arr;
    }
    return $arr;
}

/*
解决 array_flip 只能整数和字符串的问题
*/
function arr_flip($array, $no_key = null)
{
    $arr = [];
    foreach ($array as $key => $value) {
        if ($no_key) { // array_keys
            $arr[] = $key;
        } else {
            $arr[$value] = $key;
        }
    }
    return $arr;
}

/*
解决 array_merge 值重复
*/
function arr_merge($first, $second, $repeat = null)
{
    $arr = $val = $k = $last = [];
    $arr['__repeat__'] = ['first' => [], 'second' => []];
    foreach ($first as $key => $value) {
        if (!in_array($value, $val)) {
            $val[] = $value;
            if (!in_array($key, $k)) {
                $k[] = $key;
                $arr[$key] = $value;
            } else {
                $last[] = $value;
            }
        } else {
            $arr['__repeat__']['first'][$key] = $value;
        }
    }

    foreach ($second as $key => $value) {
        if (!in_array($value, $val)) {
            $val[] = $value;
            if (!in_array($key, $k)) {
                $k[] = $key;
                $arr[$key] = $value;
            } else {
                $last[] = $value;
            }
        } else {
            $arr['__repeat__']['second'][$key] = $value;
        }
    }

    foreach ($last as $value) {
        $arr[] = $value;
    }

    if (!$repeat) {
        unset($arr['__repeat__']);
    }
    return $arr;
}
