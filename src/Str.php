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