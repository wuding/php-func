<?php

namespace Func;

class Str
{
    public function __construct()
    {

    }
}

/**
 * Unicode 解码
 * 
 * @param  string $str    要解码的字符串
 * @param  bool   $type   是否 JSON 格式
 * 
 * @return array         返回解码结果
 */
function unicode_decode($str, $type = null) {
    $json = true === $type ? $str : '{"str":"' . $str . '"}';
    $obj = json_decode($json);
    if (!$obj) {
        return '';
    }
    return $obj->str;
}


/**
 * Unicode 编码
 *
 * @param      string  $str    要编码的字符串
 *
 * @return     string  返回编码结果
 */
function unicode_encode($str)
{
    $arr = array('str' => $str);
    $json = json_encode($arr);
    return $result = preg_replace('/^{"str":"|"}$/', '', $json);
}

/**
 * 字符串转换为数组
 *
 * @param      string   $str      The string
 * @param      integer  $columns  每组长度
 *
 * @return     array    ( description_of_the_return_value )
 */
function strtoarray($str, $columns = 6)
{
    $str .= '';
    $len = strlen($str);
    $arr = array();
    for ($i = $len - 1; $i > 0; $i--) {
        $row = array();
        for ($j = 0; $j < $columns; $j++) {
            if (-1 < $i) {
                $k = $i . '_' . $j;
                $row[$k] = $str{$i};
            }
            $i--;
        }
        $arr[] = $row;
        $i++;
    }
    return $arr;
}

/**
 * 将 Unicode 十六进制转 URL 编码数组
 *
 * @param      string  $string  The string
 *
 * @return     <type>  ( description_of_the_return_value )
 *
 *
 * https://zh.wikipedia.org/wiki/UTF-8
 * http://www.ruanyifeng.com/blog/2007/10/ascii_unicode_and_utf-8.html
 * https://blog.csdn.net/claram/article/details/53054195
 */
function unicode_convert($string) {
    $bin = base_convert($string, 16, 2);
    $dec = base_convert($string, 16, 10);
    $arr = strtoarray($bin, 6);

    $byte = 6;
    if ($dec < 128) {
        $byte = 1;

    } elseif ($dec < 2048) {
        $byte = 2;

    } elseif ($dec < 65536) {
        $byte = 3;

    } elseif ($dec < 2097152) {
        $byte = 4;

    } elseif ($dec < 67108864) {
        $byte = 5;
    }

    $zwf = array(
        array(),
        array(
            '0zzzzzzz',
        ),
        array(
            '10zzzzzz',
            '110yyyyy',
        ),
        array(
            '10zzzzzz',
            '10yyyyyy',
            '1110xxxx',
        ),
        array(
            '10zzzzzz',
            '10yyyyyy',
            '10xxxxxx',
            '11110www',
        ),
        array(
            '10zzzzzz',
            '10yyyyyy',
            '10xxxxxx',
            '10wwwwww',
            '111110vv',
        ),
        array(
            '10zzzzzz',
            '10yyyyyy',
            '10xxxxxx',
            '10wwwwww',
            '10vvvvvv',
            '1111110u',
        ),
    );

    $data = $zwf[$byte];
    $list = array();
    foreach ($data as $k => $value) {
        if (isset($arr[$k])) {
            $str = implode('', $arr[$k]);
            $val = strrev($value);
            $len = strlen($val);
            $row = array();
            for ($i = 0; $i < $len; $i++) {
                $col = $val{$i};
                if (preg_match('/[a-z]{1,1}/', $col)) {
                    if (isset($str{$i})) {
                        $col = $str{$i};
                    } else {
                        $col = 0;
                    }
                }
                $row[] = $col;
            }
            $str = implode('', $row);
            $str = strrev($str);
        } else {
            $str = $value;
            $str = preg_replace('/[a-z]{1,1}/', '0' , $str);
        }
        $list[] = base_convert($str, 2, 16);
    }
    $list = array_reverse($list);
    return $list;
}
