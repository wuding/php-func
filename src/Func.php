<?php

namespace php\func;

function super_globals($variable = null)
{
    $var = array();
    switch ($variable) {
        case '_SESSION':
            $var = $_SESSION;
            break;
        default:
            if (is_string($variable)) {
                $var = $GLOBALS[$variable];
            } elseif (is_null($variable)) {
                $var = $GLOBALS;
            }
            break;
    }
    return $var;
}

function globals($key = null, $value = null, $var = null)
{
    $varname = is_string($var) ? $var : null;
    $arr = is_array($var) ? $var : super_globals($varname);
    // 批量
    if (is_array($key)) {
        // 前缀
        $prefix = array();
        foreach ($key as $k => $v) {
            $index = $k;
            $val = $v;
            if (is_numeric($k)) {
                $index = $v;
                $val = null;
            }
            $pos = strpos($index, '=');
            if (false !== $pos) {
                $pre = substr($index, 0, $pos);
                $remain = substr($index, $pos + 1);
                $pieces = explode(',', $remain);
                foreach ($pieces as $piece) {
                    $keyname = "$pre.$piece";
                    $prefix[$keyname] = $val;
                }
            } else {
                $prefix[$index] = $val;
            }
        }
        // 获取项目
        $arr = array();
        foreach ($prefix as $k => $v) {
            $index = $k;
            $val = $v;
            if (is_numeric($k)) {
                $index = $v;
                $val = null;
            }
            // 别名
            $alias = null;
            $pos = strpos($index, '|');
            if (false !== $pos) {
                $pieces = explode('|', $index);
                list($index, $alias) = $pieces;
            }
            // 合法的变量名
            $item = $index;
            $rpos = strrpos($item, '.');
            if (false !== $rpos) {
                $item = str_replace('.', '_', $item);
                if (null !== $alias) {
                    $item = $alias;
                    if (!$alias) {
                        $item = substr($index, $rpos + 1);
                    }
                }
            } elseif ($alias) {
                $item = $alias;
            }
            $arr[$item] = globals($index, $val, $var);
        }
        return $arr;
    } elseif (null === $key) {
        return $arr;
    }
    // 单项
    if (array_key_exists($key, $arr)) {
        return $val = $arr[$key];
    }
    // 子项
    $pos = strpos($key, '.');
    if (false !== $pos) {
        $remain = substr($key, $pos + 1);
        $k = substr($key, 0, $pos);
        if (!array_key_exists($k, $arr)) {
            return $value;
        }
        return $var = globals($remain, $value, $arr[$k]);
    }
    return $value;
}

function session($key = null, $value = null)
{
    return globals($key, $value, '_SESSION');
}

function post($key = null, $value = null)
{
    return globals($key, $value, '_POST');
}

function get($key = null, $value = null)
{
    return globals($key, $value, '_GET');
}
