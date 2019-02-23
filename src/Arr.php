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
