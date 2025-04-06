<?php

namespace Func;

class Vars
{
    const VERSION = 25.0204;
    const REVISION = 1;

    static $server_group = [];

    function __construct()
    {
        $variable = $_SERVER;
        $array = [];
        foreach ($variable as $key => $value) {
            $var = explode('_', $key, 2);
            $count = count($var);
            if (2 > $count) {
                $array[$key] = $value;
                continue;
            }
            list($prefix, $postfix) = $var;

            if (!array_key_exists($prefix, $array)) {
                $array[$prefix] = [];
            }
            $array[$prefix][$postfix] = $value;
        }
        self::$server_group = $array;
        // print_r($array);
    }
}


/* SMT */
function server($name = null)
{
    if ($name) {
        return $_SERVER[$name] ?? null;
    }
return $_SERVER;
}

function env()
{

}

function session()
{

}

/* WTFS */
function cookie()
{

}

function get(&$orig = [], $key = null, $value = null)
{
    if ($key) {
        return $_GET[$key] ?? $value;;
    }

    $array_key_exists = array_key_exists('', $orig);
    if ($array_key_exists) {

    }
    $variable = $orig;
    $arr = [];
    foreach ($variable as $key => $value) {
        $arr[$key] = $_GET[$key] ?? $value;
    }
    return $arr;
    var_dump(get_defined_vars());

    return func_get_args();
}


function post(&$orig = [], $key = null, $value = null)
{
    if ($key) {
        return $_POST[$key] ?? $value;;
    }

    $array_key_exists = array_key_exists('', $orig);
    if ($array_key_exists) {

    }
    $variable = $orig;
    $arr = [];
    foreach ($variable as $key => $value) {
        $arr[$key] = $_POST[$key] ?? $value;
    }
    return $arr;
    var_dump(get_defined_vars());
}

function file()
{

}

/**/
function request(&$orig = [], $key = null, $value = null)
{
    $request_ = Vars::$server_group['REQUEST'] ?? [];
    if ($key) {
        return $request_[$key] ?? $value;;
    }
    $variable = $orig;
    $arr = [];
    foreach ($variable as $key => $value) {
        $arr[$key] = $request_[$key] ?? $value;
    }
    $orig = $variable;
    // print_r(get_defined_vars());
    return $arr;
}

function globals()
{

}

/**/
function lang()
{

}

/**/
function http()
{

}

/*
If I Nerver Loved You (Disco Music)
6838
Báo Bình
But my heart still lies on this cold hard ground
但我的心依旧躺在这冰冷坚硬的地面上
*/
