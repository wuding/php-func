<?php

namespace Func;

class PCRE
{
	public function __construct()
	{

	}
}

function str_match($pattern, $subject, $value = null, $type = false)
{
    $val = $value;
    $match = preg_match($pattern, $subject);
    if ($type) {
        return $f = $match ? $val : $subject;
    }
    return $z = $match ? $subject : $val;
}
