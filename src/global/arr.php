<?php

use Func as f;

new \Func\Arr;

function array_diff_kv($arr = [], $other = [], $ignore = [], $null = false)
{
    return f\array_diff_kv($arr, $other, $ignore, $null);
}

function arr_fixed_assoc(array &$array, $reset = false)
{
	return f\arr_fixed_assoc($array, $reset);
}

function arr_reset_values(array &$array, $set = [], $reset = false)
{
	return f\arr_reset_values($array, $set, $reset);
}
