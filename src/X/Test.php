<?php

namespace Func\X;

class Test
{
    public function __construct()
    {

    }
}

/**
 * Unicode 解码
 * 
 * @param  string $str    要解码的字符串
 * @param  bool   $type   ？
 * 
 * @return array         返回解码结果
 */
function unicode_decode($str, $type = null)
{
    $str = trim($str);
    if (!$type || !$str) {
        return $str;
    }

    $str = preg_replace('/\"&#13;/', '', $str);
    $obj = json_decode('{"str":"' . $str . '"}');
    return _isset($obj, 'str', '');
}
