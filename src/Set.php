<?php

namespace Func;

class Set
{
	public static $obj = [];

	public function __construct($name, $func)
    {
    	self::init($name, $func);
    }

    public static function init($name, $func)
    {
    	if (is_array($name)) {
    		foreach ($name as $key => $value) {
				self::$obj[$key] = $value;
			}
    	} else {
    		self::$obj[$name] = $func;
    	}    	
    }

    public static function call($name)
    {
    	$func = self::$obj[$name];
    	$arr = func_get_args();
    	array_shift($arr);
    	$pieces = [];
        foreach ($arr as $key => $value) {
            $s = "\$arr[$key]";
            $pieces[] = $s;
        }
        $arg = implode(', ', $pieces);
        $code = "\$what = \$func($arg);";
        eval($code);
        return $what;
    }
}
