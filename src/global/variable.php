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
