<?php

namespace Func\Example;

class Str
{
    public static function unicodeEncode($str)
    {
        return unicode_encode($str);
    }

    public static function unicodeDecode($str)
    {
        return unicode_decode($str);
    }
}

func('\Func\Str\unicode_encode', '', [], [], [], false);
func('\Func\unicode_decode', '', [], [], [], false);

$str = '举个例子';
$result = Str::unicodeEncode($str);

$str = '\u4e3e\u4e2a\u4f8b\u5b50';
# $str = '{"str":"\u4e3e\u4e2a\u4f8b\u5b50"}';
# $result = Str::unicodeDecode($str);
print_r($result);
