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
