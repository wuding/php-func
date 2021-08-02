<?php

namespace php\func;

class Func
{
    const VERSION = '21.2.25';

    /*
    配置
    */
    public static $lang = array(
        'hash_length' => 8,
        'var_ignore' => array('', null),
    );
}

// 获取超全局变量
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

// 从数组中获取指定键的值
function globals($key = null, $value = null, $var = null, $ignore = null)
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

    // 类型检测
    if (!is_array($arr)) {
        var_dump([__FILE__, __LINE__, get_defined_vars()]);
        exit;
    }

    // 单项
    if (array_key_exists($key, $arr)) {
        $val = $arr[$key];
        // 仅检测键名
        if (null === $ignore) {
            return $val;
        } elseif (is_bool($ignore)) { // 不可以是 空值
            $val = true === $ignore ? trim($val) : $val;
            if ($val) {
                return $val;
            }
        } elseif (is_array($ignore)) { // 枚举
            if (!in_array($val, $ignore)) {
                return $val;
            }
        } elseif ($ignore !== $val) { // 单个忽略
            return $val;
        }
        return $value;
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

// 服务器变量
function server($key = null, $value = null)
{
    return globals($key, $value, '_SERVER');
}

// Cookie
function cookie($key = null, $value = null)
{
    return globals($key, $value, '_COOKIE');
}

// 会话
function session($key = null, $value = null)
{
    return globals($key, $value, '_SESSION');
}

// 文件上传
function files($key = null, $value = null)
{
    return globals($key, $value, '_FILES');
}

// 表单
function post($key = null, $value = null)
{
    return globals($key, $value, '_POST');
}

// 查询
function get($key = null, $value = null, $ignore = null)
{
    return globals($key, $value, '_GET', $ignore);
}

// 语言
// 模拟 gettext 从散列表数组中读取本地化语言
function lang($message, $return_key = null)
{
    $hash = md5($message);
    $key = substr($hash, 0, Func::$lang['hash_length']);
    if (true === $return_key) {
        return $key;
    }
    return globals($key, $message, '_LANG', Func::$lang['var_ignore']);
}

// 配置
function conf($key = null, $value = null)
{
    return globals($key, $value, '_CONF');
}
