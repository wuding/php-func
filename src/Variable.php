<?php

namespace Func;

class Variable
{
    const VERSION = '21.8.3.1180';

    /*
    配置
    */
    public static $fix_type = array(
        'change' => null,
    );

    // 修正类型
    public static function fix_type($var, $value, $change = true)
    {
        $fix_type_change = static::$fix_type['change'];
        $type = gettype($var);
        $types = gettype($value);
        $changed = null !== $fix_type_change ? $fix_type_change : $change;
        if ($type !== $types && $changed) {
            return $value;
        }
        return $var;
    }
}
