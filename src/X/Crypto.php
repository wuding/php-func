<?php

namespace Func\X;

class Crypto
{

}

// 解密 <prefix>_<key>_<md5>
function separator_decrypt($string, $separator = '_', $info = null)
{
    $array = explode($separator, $string);
    $prefix = array_shift($array);
    $md5 = array_pop($array);
    $key = $array ? implode($separator, $array) : null;
    $name = $prefix . $separator . $key;
    if (null === $info) {
        return $name;
    }
    return get_defined_vars();
}

// 加密 <prefix>_<key>[secret]_<md5>
function separator_encrypt($string, $secret = '', $separator = '_')
{
    $str = $string . $secret;
    $md5 = md5($str);
    return $full = $string . $separator . $md5;
}

// 校验加密的字符串
function separator_verify($string, $secret = '', $separator = '_')
{
    $decrypt = separator_decrypt($string, $separator);
    $encrypt = separator_encrypt($decrypt, $secret, $separator);
    return $verify = $string === $encrypt;
}