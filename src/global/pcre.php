<?php

use Func as f;

new \Func\PCRE;

function str_match($pattern, $subject, $value = null, $type = false)
{
	return f\str_match($pattern, $subject, $value, $type);
}
