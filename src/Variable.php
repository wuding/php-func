<?php

namespace Func;

class Variable
{
	public function __construct()
	{

	}
}

/**
 * null合并运算符
 * 
 * http://php.net/manual/zh/migration70.new-features.php
 *
 * @param      array|object   $arr    数组对象
 * @param      string         $key    键名
 * @param      mixed          $value  默认值
 *
 * @return     mixed                  运算结果
 */
function _isset($arr, $key = '', $value = null)
{
    if (is_object($arr)) {
        $arr = (array) $arr;
    }

    // 大于等于 7.0
    if (version_compare(phpversion(), '7.0.0', '>=')) {
        eval("\$result = \$arr[\$key] ?? \$value;");
        return $result;
    }

    // 低版本
    return isset($arr[$key]) ? $arr[$key] : $value;
}

function _unset($arr, $keys = [])
{
    $reg = [];
    // 按键名
    foreach ($keys as $val) {
        if (preg_match('/\//', $val)) {
            $reg[] = $val;
        } else {
            unset($arr[$val]);
        }
    }
    // 正则
    foreach ($reg as $exp) {
        foreach ($arr as $key => $value) {
            if (preg_match($exp, $key)) {
                unset($arr[$key]);
            }
        }
    }
    return $arr;
}