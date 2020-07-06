<?php

use Func as f;

new \Func\Variable;

function _isset($arr, $key = '', $value = null)
{
    return f\_isset($arr, $key, $value);
}

function _unset($arr, $keys = [])
{
    return f\_unset($arr, $keys);
}

function globals(array $vars = [])
{
    return f\globals($vars);
}

function _define($name, $value = null)
{
    return f\_define($name, $value);
}

function _defined($name)
{
    return f\_defined($name);
}

function _constant($name, $value = null)
{
    return f\_constant($name, $value);
}

function request_scheme($vars = null)
{
    return f\request_scheme($vars);
}

function get()
{
    return call_user_func_array("\Func\get", func_get_args());
}
