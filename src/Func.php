<?php

#$GLOBALS['FUNC_FILES'] = [];

if (!defined('FUNC_X_PATH')) {
	define('FUNC_X_PATH', __DIR__ . '/X');
}

global $_CONST;
$_CONST = [];

class Func
{
    public static $obj = [];

    public function __construct()
    {
        #self::init($name, $func);
    }

    public static function set($name, $func)
    {
        #print_r([__LINE__, __FILE__, $name, $func, self::$obj]);
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                self::$obj[$key] = $value;
            }
        } else {
            self::$obj[$name] = $func;
        }       
    }

    public static function call($name = null)
    {
        
        $func = self::$obj[$name];
        $arr = func_get_args();
        array_shift($arr);
        #print_r([__LINE__, __FILE__, $name, $arr, $func, self::$obj]);
        return call_user_func_array($func, $arr);
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

function func($variable = [], $classes = [])
{
    if (is_string($variable)) {
        $arr = func_get_args();
        array_shift($arr);
        $variable = [$variable => $arr];
        $classes = [];
    }

    foreach ($classes as $key => $value) {
        $value = trim($value);
        if (preg_match('/^([A-Z]+)/', $value)) {
            $GLOBALS['FUNC_FILES'][] = $nm = "\Func\\$value";
            $obj = new $nm;
        } else {
        	$value = preg_replace('/\\+/', '/', $value);
            $filename = strtolower($value);
            $dir = 'global';
            if (preg_match('/^x\//', $filename)) {
            	$split = preg_split('/\//', $filename);
                $filename = array_pop($split);
                $dir = preg_replace('/(\\\|\/)$/', '', trim(FUNC_X_PATH));
                $filename = 'global/' . $filename;
            }
            $filename .= '.php';
            $filename = "$dir/$filename";
            # echo $filename . PHP_EOL;
            require $filename;
            $cls[] = $value;
        }
    }

    foreach ($variable as $key => $value) {
        #print_r([$key, $value]);
        
        $value = is_array($value) ? $value : [$value];
        $key = is_numeric($key) ? $value : $key;
        #print_r([$key, $value]);
        
        $origin = $key;
        $name = array_shift($value);

        $len = count($value);
        $str = '';
        $pieces = [];
        for ($i = 0; $i < $len; $i++) {
            $s = "\$value[$i]";
            $pieces[] = $s;
        }
        $arg = implode(', ', $pieces);
        $arg = $arg ? ", $arg" : '';

        if (is_object($name)) {
            $func = $name;
            $origin = "\$func";
            $name = $key;
        } else {
            $origin = "'$origin'";
        }

        $export = '';
        if (is_array($name)) {
            $export = var_export($name, true);
            $export = preg_replace('/[\r\n]+/', '', $export);
        } else {
            $export = "'$name'";
        }
        # print_r([__LINE__, __FILE__, get_defined_vars()]);

        $code = "new \Func\Alias($origin, $export$arg);";
        eval($code);
    }
}
