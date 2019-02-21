<?php

function func($variable = [], $classes = [])
{
    if (is_string($variable)) {
        $arr = func_get_args();
        array_shift($arr);
        $variable = [$variable => $arr];
        $classes = [];
    }

    foreach ($classes as $key => $value) {
        $nm = "\Func\\$value";
        $obj = new $nm;
    }

    foreach ($variable as $key => $value) {
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

        $code = "new \Func\Alias($origin, $export, $arg);";
        eval($code);
    }
}
