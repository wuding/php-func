<?php

namespace php\func;

class Func
{
    const VERSION = 25.0201;
    const REVISION = 9;

    /*
    配置
    */
    public static $lang = array(
        'hash_length' => 8,
        'var_ignore' => array('', null),
    );

    public static $ini = array(
        'request_order' => 'GP',
        'variables_order' => 'EGPCS',
    );

    public static function request($key = null, $value = null)
    {
        $https = server('HTTPS');
        $scheme = 'on' === $https ? 'https' : null;

        $server_request = array(
            'URI' => null,
            'METHOD' => null,
            'TIME_FLOAT' => null,
            'TIME' => null,
            'SCHEME' => $scheme,
        );

        $variable = is_array($key) ? $key : [];
        foreach ($variable as $ke => $val) {
            if (is_numeric($ke)) {
                $ke = $val;
                $val = null;
            }
            $k = strtoupper($ke);
            if (array_key_exists($k, $server_request)) {
                $key[$ke] = self::request($k, $val);
            }
        }

        if (!is_array($key)) {
            $k = strtoupper($key);
            if (array_key_exists($k, $server_request)) {
                $ke = "REQUEST_$k";
                $srv = server($ke, $value);
                return $srv;
            }
        }

        return globals($key, $value, '_REQUEST');
    }

    public static function getenv($name = null, $value = null, $var_array = [])
    {
        global $_ENV;
        $get = null;
        $set = null;
        $put = null;
        if (is_array($var_array)) {
            extract($var_array);
        }

        // null
        if (!is_int($get)) {
            return globals($name, $value, '_ENV');
        }

        $env = [];
        if (is_null($name)) {
            $env = getenv();
            if (is_array($value)) {
                if (2 === $get) {
                    $env = array_merge($env, $value);
                } elseif (3 === $get) {
                    $env = array_merge($value, $env);
                }
            }
            return $env;
        } elseif (is_string($name)) {
            $get_local = getenv($name, true) ?: $value;
            $get_global = getenv($name) ?: $value;
            $env = 4 === $get ? $get_local : $get_global;
        }

        $variable = is_array($name) ? $name : [];
        foreach ($variable as $key => $val) {
            if (is_numeric($key)) {
                $key = $val;
                $val = $value;
            }

            $get_local = getenv($key, true) ?: $val;
            $get_global = getenv($key) ?: $val;
            $v = 4 === $get ? $get_local : $get_global;
            $env[$key] = $v ?: ($val ?: $v);
        }
        return $env;
    }

    public static function setenv($name = null, $value = null, $var_array = [])
    {
        global $_ENV;
        $get = null;
        $set = null;
        $put = null;
        if (is_array($var_array)) {
            extract($var_array);
        }

        // null
        if (!is_bool($set)) {
            return globals($name, $value, '_ENV');
        }

        $env = [];
        if (is_null($name)) {
            $env = $_ENV = $value;

        } elseif (is_string($name)) {
            if (true === $set) {
                if (!array_key_exists($name, $_ENV)) {
                    $env = $_ENV[$name] = $value;
                }
            } else {
                $env = $_ENV[$name] = $value;
            }
        }

        $variable = is_array($name) ? $name : [];
        foreach ($variable as $key => $val) {
            if (is_numeric($key)) {
                $key = $val;
                $val = $value;
            }

            if (true === $set) {
                if (!array_key_exists($key, $_ENV)) {
                    $env[$key] = $_ENV[$key] = $val;
                }
            } else {
                $env[$key] = $_ENV[$key] = $val;
            }
        }
        return $env;
    }

    public static function putenv($name = null, $value = null, $var_array = [])
    {
        global $_ENV;
        $get = null;
        $set = null;
        $put = null;
        if (is_array($var_array)) {
            extract($var_array);
        }

        // null
        if (!$put) {
            return self::setenv($name, $value, $var_array);
        }

        $env = [];
        if (is_string($name)) {
            $query_data = [$name => $value];
            $assignment = http_build_query($query_data);
            $env = putenv($assignment);
        }

        $variable = is_array($name) ? $name : [];
        foreach ($variable as $key => $val) {
            if (is_numeric($key)) {
                $key = $val;
                $val = $value;
            }

            $query_data = [$key => $val];
            $assignment = http_build_query($query_data);
            $env[$key] = putenv($assignment);
        }
        return $env;
    }

    public static function arg_key_format($var)
    {
        if (is_array($var)) {
            return $var;

        } elseif (is_string($var)) {
            $strpos = strpos($var, ',');
            if (false === $strpos) {
                return $var;
            }

        } else {
            print_r([__LINE__, __FILE__]);
            var_dump(get_defined_vars());
            exit;
        }

        $pattern = "/[,\s]/";
        $pattern2 = "/=/";
        $variable = preg_split($pattern, $var);
        $arr = [];
        foreach ($variable as $key => $value) {
            $k = $value;
            $v = null;
            $preg_split = preg_split($pattern2, $value);
            $count = count($preg_split);
            if (1 < $count) {
                list($k, $v) = $preg_split;
                $arr[$k] = $v;
            } else {
                $arr[] = $k;
            }

            // var_dump($preg_split);
        }

        return $arr;
    }
}

// 获取超全局变量
function super_globals($variable = null)
{
    $var = array();
    switch ($variable) {
        case '_SESSION':
            $var = $_SESSION;
            break;
        case '_REQUEST':
            $var = $_REQUEST;
            break;
        default:
            if (is_string($variable)) {
                $var = $GLOBALS[$variable] ?? null;
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
            $arr[$item] = globals($index, $val, $var, $ignore);
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

    $arg_type = is_string($key) || is_int($key);
    if (!$arg_type) {
        return false;
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
    $queryData = globals($key, $value, '_GET', $ignore);
    $special = in_array($key, [null, false, true], true);
    if ($special && $value) {
        $arg_key_format = Func::arg_key_format($value);
        $additional = globals($arg_key_format, null, '_GET', $ignore);

        $queryData = false === $key ? $additional : array_merge($queryData, $additional);

    }
    return $queryData;
}

function request($key = null, $value = null)
{
    return Func::request($key, $value);
}

function env($name = null, $value = null, $var_array = [])
{
    global $_ENV;
    $get = null;
    $set = null;
    $put = null;
    if (is_array($var_array)) {
        extract($var_array);
    } elseif (is_int($var_array)) {
        $get = $var_array;
    } elseif (is_bool($var_array)) {
        $set = $var_array;
    } elseif (is_null($var_array)) {
        $put = true;
    }

    $var_array = [
        'get' => $get,
        'set' => $set,
        'put' => $put,
    ];

    if (is_int($get)) {
        return Func::getenv($name, $value, $var_array);
    } elseif (is_bool($set)) {
        return Func::setenv($name, $value, $var_array);
    } elseif ($put) {
        return Func::putenv($name, $value, $var_array);
    }
    return globals($name, $value, '_ENV');
}

function response($key = null, $value = null)
{
    return globals($key, $value, 'http_response_header');
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
