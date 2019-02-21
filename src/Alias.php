<?php

namespace Func;

class Alias
{
	public function __construct($origin, $name)
    {
        if (!isset($GLOBALS['FUNC_ARGS'])) {
            $GLOBALS['FUNC_ARGS'] = [];
        }
        /*
        if (!isset($GLOBALS['FUNC_ARGS'][$name])) {
            $GLOBALS['FUNC_ARGS'][$name] = [];
        }
        */
        $anonymous = null;
        if (is_object($origin)) {
            $set = new Set($name, $origin);
            $origin = "\Func\Set::call";
            $anonymous = "'$name'";

        } elseif (is_object($name)) {
            $set = Set::init($origin, $name);
            $name = $origin;
            $origin = "\Func\Set::call";
            $anonymous = "'$name'";
        }

        if ($name == $origin) {
            $info = [$name, $origin];
            $info['line'] = __LINE__;

            $this->debug('', $info, 1);
        }

        $GLOBALS['FUNC_ARGS'][] = $name;
        $total = count($GLOBALS['FUNC_ARGS']);
        $ns = '';
        if (preg_match('/(.*)\\\([a-z_]+)$/i', $name, $matches)) {
            # print_r($matches);
            $ns = $matches[1];
            $name = $matches[2];
        }
        $origin_ns = '';
        $origin_type = '\\';
        $origin_name = $origin;
        if (preg_match('/(.*)(\\\|::)([a-z_]+)$/i', $origin, $matches)) {
            # print_r($matches);
            $origin_ns = $matches[1];
            $origin_type = $matches[2];
            $origin_name = $matches[3];
        }
        if (!$name) {
            $name = $origin_name;
        }
        $namespace = $ns;
        $namespace = preg_replace('/^\\\|\\\$/', '', $namespace);
        


        $num = func_num_args();
        $arr = [];
        $params = [];
        $alphabet = '_ abcdefghijklmnopqrstuvwxyz';
        for ($i = 2; $i < $num; $i++) {
            $j = $i - 2;
            $str = '';
            $value = func_get_arg($i);
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';

            } elseif (is_null($value)) {
                $value = 'null';

            } elseif(is_string($value)) {
                $value = "'$value'";

            } elseif(is_numeric($value)) {
                
            } elseif(is_array($value)) {            
                # $GLOBALS['FUNC_ARGS'][$name][$j] = $value;
                $nm = "FUNC_ARGS_$total" . "_$j";
                define($nm, $value);
                # $value = "\$GLOBALS['FUNC_ARGS']['$name'][$j]";
                $value = "$nm";
            } else {
                var_dump($value);
                print_r([__FILE__, __LINE__]);
                exit;
            }
            $key = $alphabet[$i];
            $str = '$' . "$key = $value";
            # echo $str . PHP_EOL;
            $arr[] = $str;
            $params[] = "\$$key";
        }
        if ($anonymous) {
            array_unshift($params, $anonymous);
        }
        $arg = implode(', ', $arr); #echo 
        $param = implode(', ', $params);
        # print_r($GLOBALS['FUNC_ARGS']);# 
        $arr = [];
        foreach ($GLOBALS as $k => $v) {
            $arr[] = $k;
        }
        # print_r($arr);
        if (preg_match('/^\\\Func\\\([a-z_]+)\\\(.*)$/i', $origin, $matches)) {
            # print_r($matches);
            $nm = '\Func\\' . $matches[1];
            $obj = new $nm;
            $origin_ns = '';
            $origin_name = $matches[2];
        }
        $origin_ns = $origin_ns ? : '\Func';
        
        
        # $origin_name = $origin_name ? : $name;
        $origin = "$origin_ns$origin_type$origin_name";

        $str = "namespace $namespace { function $name($arg) { return $origin($param); } }"; #
        $class = $name;
        if ($namespace) {
            $class = "\\$namespace\\$name";     
        }
        $origin = preg_replace('/^\\\|\\\$/', '', $origin);

        

        $info = [
            'namespace' => $namespace, 
            'name' => $name, 
            'class' => $class, 
            'origin' => $origin, 
            func_get_args(), 
            __FILE__, 
            'line' => __LINE__
        ];
        
        if ($class == $origin) {
            $info['line'] = __LINE__;
            $this->debug($str, $info, 1);
        }
        if (!function_exists($class)) {
            eval($str);
        } else {
            $info['info'] = "Cannot redeclare $name()";
            $info['line'] = __LINE__;
            $this->debug($str, $info);
        }
    }

    function debug($str, $arr, $exit = null)
    {
        echo $str;
        print_r($arr);
        if ($exit) {
            exit;
        }
    }
}
