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

    public static function unicodeConvert($str)
    {
        return unicode_convert($str);
    }

    public static function strToArray($str)
    {
        return strtoarray($str);
    }
}

$functions = [];
func($functions, ['str']);

/*
func('\Func\Str\unicode_encode', '', [], [], [], false);
func('\Func\unicode_decode', '', [], [], [], false);
func('\Func\unicode_convert', '', [], [], [], false);
*/

$str = '举个例子';
$result = Str::unicodeEncode($str);

$str = '\u4e3e\u4e2a\u4f8b\u5b50';
# $str = '{"str":"\u4e3e\u4e2a\u4f8b\u5b50"}';
# $result = Str::unicodeDecode($str);

/**/
// 十进制转十六进制转 URL 解码转 Unicode 码位
$str = base_convert('129412', 10, 16);
# $str = '5434';
$result = Str::unicodeConvert($str);
$result = uni_convert_encoding($str);
$result = url_convert_encoding($result);
$result = url_decode($result, true);
$result = base_convert($result, 10, 16);

/*
// URL 解码转 HTML 实体转 UTF-8 转 URL 编码
$str = '%F0%9F%A6%84';
$result = urldecode($str);
# $result = '🦄';
$result = mb_convert_encoding($result, 'HTML-ENTITIES', 'UTF-8');
# $result = '&#129412;';
$result = '&#x1F984;';
$result = mb_convert_encoding($result, 'UTF-8', 'HTML-ENTITIES');
# $result = urlencode($result);
*/

/*
// 十六进制转二进制转数组
$str = '1F984';
$result = base_convert($str, 16, 2);
# $result = '11111100110000100';
$result = strtoarray($result);
*/
print_r($result);
