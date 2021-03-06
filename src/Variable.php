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

    /*
    // 大于等于 7.0
    if (version_compare(phpversion(), '7.0.0', '>=')) {
        eval("\$result = \$arr[\$key] ?? \$value;");
        return $result;
    }
    */

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

function globals(array $vars = [])
{
    $vars[] = 'GLOBALS';
    $arr = [];
    foreach ($GLOBALS as $key => $value) {
        if (!in_array($key, $vars)) {
            $arr[$key] = $value;
        }
    }
    return $arr;
}

function _define($name, $value = null)
{
    global $_CONST;
    $_CONST[$name] = $value;
}

function _defined($name)
{
    global $_CONST;
    return array_key_exists($name, $_CONST);
}

function _constant($name, $value = null)
{
    global $_CONST;
    if (_defined($name)) {
        $value = $_CONST[$name];
    }
    return $value;
}

function request_scheme($vars = null)
{
    $vars = null === $vars ? $_SERVER : $vars;
    $https = _isset($vars, 'HTTPS');
    $scheme = ('on' == strtolower($https)) ? 'https' : 'http';
    return $request_scheme = _isset($vars, 'REQUEST_SCHEME', $scheme);
}

function request_url($vars = null, $arr = null)
{
    $vars = $vars ?: $_SERVER;
    $scheme = request_scheme($vars);
    $host = $vars['HTTP_HOST'] ?? '<err>';
    $uri = $vars['REQUEST_URI'] ?? '<err>';
    $url = "$scheme://$host$uri";
    if (!$arr) {
        return $url;
    }
    $URL = parse_url($url);
    $URL['url'] = $url;
    return $URL;
}

/**
 * 获取查询项的值
 *
 */
function get($key = null, $value = null)
{
    // 获取多项
    if (is_object($key)) {
        $string = $key->scalar;
        $variable = explode(',', $string);
        $arr = array();
        foreach ($variable as $key) {
            $arr[$key] = get($key);
        }
        return $arr;
    }

    // 获取查询数据
    $arr =  \Func\Str::parse_string();

    // 检测键值
    if (null !== $key) {
        if (array_key_exists($key, $arr)) {
            return $arr[$key];
        }
        return $value;
    }
    return $arr;
}
