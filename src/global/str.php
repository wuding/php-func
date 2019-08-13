<?php

use Func as f;

new \Func\Str;

function unicode_decode($str, $type = null)
{
    return f\unicode_decode($str, $type);
}

function unicode_encode($str)
{
    return f\unicode_encode($str);
}

function strtoarray($str, $columns = 6)
{
    return f\strtoarray($str, $columns);
}

function unicode_convert($string)
{
    return f\unicode_convert($string);
}

function uni_convert_encoding($str)
{
    return f\uni_convert_encoding($str);
}

function url_convert_encoding($arr, $type = null)
{
    return f\url_convert_encoding($arr, $type);
}

function url_decode($str, $type = null)
{
    return f\url_decode($str, $type);
}
