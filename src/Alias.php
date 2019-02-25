<?php

namespace Func;

class Alias
{
    public static $count = 0;
    public static $total = null;
    public static $global = [];
    public static $const = [];
    public static $prefix = null;
    public static $debug_exist = null;
    public static $unique = [];

    public function __construct($origin, $name)
    {
        /*if (!isset($GLOBALS['FUNC_ARGS'])) {
            $GLOBALS['FUNC_ARGS'] = [];
        }
        
        if (!isset($GLOBALS['FUNC_ARGS'][$name])) {
            $GLOBALS['FUNC_ARGS'][$name] = [];
        }
        */
        
        $args = func_get_args();
        array_shift($args);
        array_shift($args);
        $anonymous = 0;
        $init = $config = null;
        $keys = $values = [];

        if (is_object($origin)) {
            $set = \Func::set($name, $origin);
            $origin = "\Func::call";
            $anonymous = "'$name'";
            #print_r([__LINE__, __FILE__, $name, $origin]);

        } elseif (is_object($name)) {

            $set = \Func::set($origin, $name);
            $name = $origin;
            $origin = "\Func::call";
            $anonymous = "'$name'";
            #print_r([__LINE__, __FILE__, $name, $origin]);

        } elseif (is_array($name)) {
            $ini = isset($name[1]) ? $name[1] : [];
            $name = $name[0];
            
            /*$GLOBALS['FUNC_ARGS'][] = $name;
            self::$total = count($GLOBALS['FUNC_ARGS']);*/
            self::$const[] = $name;
            self::$total = count(self::$const);

            $var_array = self::param($ini, ', ', 'config', 'init', 'keys', 'values');
            extract($var_array);
        }



        if ($name == $origin) {
            if (preg_match('/^\\\(.*)/i', $origin, $matches)) {
                print_r($matches);
                $info = [$name, $origin];
                $info['line'] = __LINE__;
                $info['file'] = __FILE__;

                $this->debug('', $info, 1);
            }
            $origin = "\Func\\$origin";
        }

        self::$const[] = $name;
        self::$total = count(self::$const);
        $ns = '';
        $origin_type = '\\';
        if (preg_match('/(.*)(\\\|::|->)(.*)$/i', $name, $matches)) {
            $ns = $matches[1];
            $origin_type = $matches[2];
            $name = $matches[3];

        }
        $origin_ns = '';
        
        $origin_name = $origin;
        if (preg_match('/(.*)(\\\|::|->)(.*)$/i', $origin, $matches)) {
            $origin_ns = $matches[1];#
            $origin_type = $matches[2];
            $origin_name = $matches[3];
            if ('->' == $origin_type) {
                if (!$origin_name) {
                    $origin_name = '__construct';
                }
                if (!$ns) {
                    #$ns = $origin_ns;
                }
            }
            #print_r([__LINE__, __FILE__, $origin_type, $is_class, $wtf, $origin, $name, $anonymous]);
        }
        if (!$name) {
            $name = $origin_name;
        }
        $namespace = $ns;
        $namespace = preg_replace('/^\\\|\\\$/', '', $namespace);
        


        $var_array = self::param($args, null, 'defines', 'names', 'keys', 'values');
        extract($var_array);
        if ($anonymous) {
            array_unshift($names, $anonymous);
        }
        $arg = implode(', ', $defines); #echo 
        $param = implode(', ', $names);
        $key_var = implode(", \\\$", $keys);
        $origin_key = $keys;
        $origin_val = $values;
        $keys = implode("', '", $keys);
        $values = implode(', ', $values);
        $key_var = $key_var ? "[\\\$$key_var]" : '[]';
        $keys = $keys ? "['$keys']" : '[]';
        $values = $values ? "[$values]" : '[]';
        # print_r($GLOBALS['FUNC_ARGS']);# 
        $arr = [];
        foreach ($GLOBALS as $k => $v) {
            $arr[] = $k;
        }
        # print_r($arr);
        $nm = '';
        $dir = '';
        $file = '';
        if (preg_match('/^\\\Func\\\([a-z_]+)\\\(.*)$/i', $origin, $matches)) {
            $nme = $matches[1];
            $postfix = $matches[2];
            $split = preg_split('/\\\/', $postfix);
            $count = count($split);
            $prefix = '';
            if (1 < $count) {
                $prefix = $nme;
                $file = array_pop($split);
                $str = implode('\\', $split);
                $nme .= "\\$str";
                $dir = preg_replace('/(\\\|\/)$/', '', trim(FUNC_X_PATH)) . '/';
            }
            if (!in_array($nme, ['Ext', 'X'])) {
                
                $nm = '\Func\\' . $nme;
                $obj = new $nm;
                $origin_ns = '';
                $origin_name = $matches[2];
                if ($prefix) {
                    $origin_name = "$prefix\\$origin_name";
                    $nme = array_pop($split);
                }
                
                $filename = strtolower($nme);
                $filename .= '.php';
                $filename = $dir . "global/$filename";
                # echo $filename . PHP_EOL;
                require $filename;
                /**/
            }
            
        } elseif (preg_match('/^\\\Func\\\(.*)$/i', $origin, $matches)) {
            $nm = '\Func\\' . $matches[1];
        }
        $origin_ns = $origin_ns ? : '\Func';

        
        
        
        # $origin_name = $origin_name ? : $name;
        $original = $origin;
        $origin = "return $origin_ns$origin_type$origin_name";
        #print_r([__LINE__, __FILE__, $name, $origin]);
        $is_class = 0;
        $wtf = 0;
        $class = '';
        if (in_array($origin_type, ['->', '::']) && !$nm) {
            $wtf = 1;
            $class = $origin_ns;
            # $namespace = '';
            if (in_array($name, ['__construct'])) {
                
                
                $origin = "$class";
                $origin = "return new $class";
            } else {
                
                $origin = "$origin_type$origin_name";
                $origin = "return self::\$obj$origin_type$origin_name";
                $is_class = 1;
            }
        }
        $classes = addslashes($class);


        # print_r(get_defined_vars());
        $string = <<<HEREDOC
        namespace $namespace {
            class $name {
                public \$ob;
                public static \$obj;
                public static \$str = '';
                public static \$arg = $values;
                public static \$args = $keys;
                public static \$vars = [];
                public static \$params = [];
                public static \$cache = [];
                public static \$func = '$origin_name';
                public static \$type = '$origin_type';
                public static \$from = '';
                public static \$new = null;
                public \$line = [];
                static \$instances = 0;
                public \$instance;

                public function __construct($config) {
                    \$this->instance = ++self::\$instances;
                    \$this->line[] = 0;
                    
                    if (self::\$new) {
                        self::\$params = self::\$new;
                        self::init();
                        
                    } elseif (false === self::\$new) {
                        self::init();
                    } else {
                        self::init($init);
                    }
                }

                public static function init() {
                    \$args = func_get_args();
                    
                    

                    
                    /*if (isset(self::\$cache[\$json])) {
                        return self::\$cache[\$json];
                    }*/
                    /**/
                    if (self::\$new) {
                        \Func\Alias::\$prefix = 'prefix';
                        \$args = self::\$arg = self::\$new;
                        \$var_array = \Func\Alias::param(\$args, ', ', 0, 'init', 'keys');
                        
                        \$init = \$var_array['init'];                        
                        self::\$args = \$var_array['keys'];
                        \$vars = self::args(\$args);
                        extract(\$vars);
                        \$json = md5(json_encode(\$vars));
                        \$code = "\\\$obj = new $classes(\$init);";
                        eval(\$code);


                    } else {
                        \$vars = self::args(\$args);
                        
                        extract(\$vars);
                        \$json = md5(json_encode(\$vars));
                        \$obj = new $class($init);
                    }
                    
                    \Func\Alias::\$prefix = self::\$new = null;
                    return self::\$cache[\$json] = self::\$obj = \$obj;
                }

                public static function run($arg) {
                    
                    
                    self::inst();
                    \$args = func_get_args();
                    
                    \Func\Alias::\$prefix = 'prefix';
                    \$var_array = \Func\Alias::param(\$args, ', ', 0, 'init', 'keys');
                    
                    \$init = \$var_array['init'];                        
                    \$keys = \$var_array['keys'];
                    \$vars = self::args(\$args, \$keys, __METHOD__);
                    extract(\$vars);

                    if ($is_class && !$anonymous) {
                        \$obj = self::obj(\$args);
                        /*\$result = 240;
                        \$code = "\\\$result = \\\$obj->test(\\\$a, \\\$b, \\\$c);";*/
                        \$code = "\\\$result = \\\$obj$origin_type$origin_name(\$init);";
                        eval(\$code);
                        return \$result;
                    } else {
                        $origin($param); 
                    }
                    
                }

                public static function obj(\$args)
                {
                    

                    \Func\Alias::\$prefix = 'prefix';
                    \$var_array = \Func\Alias::param(\$args, ', ', 0, 'init', 'keys', 'values');
                    \$init = \$var_array['init'];                        
                    \$keys = \$var_array['keys'];
                    \$vars = self::args(\$args, \$keys, __METHOD__);
                    extract(\$vars);

                    if (null !== self::\$obj) {
                        return \$obj = self::\$obj;
                        
                        \$code = "\\\$obj = \\\$obj->init(\$init);";
                    } else {
                        \$code = "\\\$obj = new static(\$init);";
                    }

                    
                    eval(\$code);
                    return \$obj;
                }

                public static function inst() {
                    
                    if (null !== self::\$obj) {
                        return self::\$obj;                        
                    }

                    \$args = func_get_args();
                    /*var_dump(\$args);*/
                    \$args = \$args ? \$args : self::\$params;
                    
                    extract(self::args(\$args, null, __METHOD__));                        
                    \$instance = new static($init);
                    \$instance->line[] = 1;
                    return \$instance;
                }

                public static function args(\$args, \$keys = null, \$from = null)
                {
                    \$args = \$args ? \$args : self::\$params;
                    \$from = (null !== \$from) ? \$from : self::\$from;
                    \$keys = \$keys ? : self::\$args;
                    foreach (\$args as \$k => \$v) {
                        \$key = \$keys[\$k];
                        self::\$vars[\$key] = \$v;
                        
                    }

                    return self::\$vars;
                }

                public function __invoke() {
                    self::\$from = __METHOD__;
                    \$args = func_get_args();
                    if (\$args) {
                        self::\$params = \$args;
                    } else {
                        \$args = self::\$params;
                    }
                   
                    /*self::init();*/
                    \$obj = self::obj(\$args);
                    
                    return \$obj;
                }


                public function __toString() {
                    return self::\$str = json_encode(\$this->line);
                }

                public function __sleep()
                {
                    return \$this->line;
                }



                public function __wakeup()
                {
                    \$this->connect();
                }

                public function contect()
                {
                    return \$this;
                }

                public static function __set_state(\$an_array)
                {
                    \$obj = new static;
                    foreach (\$an_array as \$key => \$val) {
                        \$obj->\$key = \$val;
                    }                    
                    return \$obj;
                }

                public function __debugInfo() {
                    return [
                        'obj' => self::\$obj,
                        'str' => self::\$str,
                        'arg' => self::\$arg,
                        'args' => self::\$args,
                        'func' => self::\$func,
                        'type' => self::\$type,
                    ];
                }

                public function __clone() {
                    \$this->instance = ++self::\$instances;
                }

                public static function call(\$name, \$arguments, \$type = '->') 
                {
                    self::\$params = \$arguments;
                    self::inst();

                    \$func = self::\$obj;
                    \$arg = \$arguments;
                    
                    \$str = [];
                    foreach (\$arg as \$key => \$value) {
                        \$str []= "\\\$arg[\$key]";
                    }
                    \$str = implode(', ', \$str);
                    
                    \$code = "\\\$result = \\\$func\$type\\\$name(\$str);";
                    eval(\$code);
                    return \$result;
                }

                public static function func(\$name, \$type = '->') 
                {
                    if (is_bool(\$name)) {
                        \$args = func_get_args();
                        array_shift(\$args);
                        \$args = \$name ? \$args : \$type;
                        self::\$new = \$args;

                    } elseif (is_null(\$name)) {
                        self::\$new = self::\$arg;

                    } elseif (is_string(\$name)) {
                        self::\$func = \$name;
                        if (is_string(\$type)) {
                            self::\$type = \$type; 
                        }
                       
                    }

                    self::\$from = __METHOD__ . ' ' . var_export(\$name, true);
                    return new static;
                }

                public function __call(\$name, \$arguments) 
                {
                    return self::call(\$name, \$arguments);
                }

                public static function __callStatic(\$name, \$arguments) 
                {

                    return self::call(\$name, \$arguments, '::');
                }

                public function __destruct() {
                    \$this->line[] = 2;
                }
            }

            function $name($config) {
                \$func = new $name();
                \$f = \$func::\$func;
                \$t = \$func::\$type;
                \$args = func_get_args();
                \$func::\$params = \$args;
                \$vars = \$func::args(\$args, null, __METHOD__);
                
                extract(\$vars);
                

                eval("\\\$arguments = $key_var;");
                if (!\$f) {                    
                    return \$func::inst();
                }
                return \$func::call(\$f, \$arguments, \$t);
            }
        }
HEREDOC;

        if (!$wtf) {

        $string = <<<HEREDOC
namespace $namespace { 
    function $name($arg)
    { 
        \$arr = func_get_args();
        \$arr = [];

        \$arg = $values;
        \$args = $keys;
        \$i = 0;
        foreach (\$arg as \$key => \$value) {
            \$var_name = \$args[\$i];
            \$val = &$\$var_name;
            if (is_string(\$val) && preg_match('/^__CONST__\.(.*)/', \$val, \$matches)) {
                \$val = \Func\_constant(\$matches[1]);
            }
            \$arr[] = \$val;
            \$i++;
        }
        \$arr = \Func\Alias::args($values, $keys, func_get_args());

        return \Func\Alias::call(
            '$origin_ns$origin_type$origin_name', 
            \$arr
        );
    } 
}
HEREDOC;
        } elseif ($anonymous) {
            $string = <<<HEREDOC
namespace $namespace {
    function $name($arg)
    {
        \$arg = $values;
        \$args = $keys;
        \$i = 0;
        foreach (\$arg as \$key => \$value) {
            \$var_name = \$args[\$i];
            \$val = &$\$var_name;
            if (is_string(\$val) && preg_match('/^__CONST__\.(.*)/', \$val, \$matches)) {
                \$val = \Func\_constant(\$matches[1]);
            }
            \$i++;
        }
        \$arr = \Func\Alias::args($values, $keys, func_get_args(), 1);
        extract(\$arr);
        /*print_r([521, get_defined_vars(), $arg]);*/
        return $original($param);
    }
}
HEREDOC;
            #print_r([__LINE__, $string]);

        }

        
        $str = preg_replace('/[\r\n]+/', '', $string);
       
        

        

        $class = $name;
        if ($namespace) {
            $class = "\\$namespace\\$name";     
        }
        $origin = preg_replace('/^\\\|\\\$/', '', $origin);

        $args = json_encode($args);
        $unique = "$class($args) $origin_ns$origin_type$origin_name";
        $hash = md5($unique);
        
        if (!isset(self::$unique[$hash])) {
            self::$unique[$hash] = $unique;
        } else {
            $info['info'] = "Cannot redeclare $name()";
            $info['line'] = __LINE__;
            #self::debug($is_class ? $string : $str, $info, 1);
            #print_r([__LINE__, __FILE__, $unique, $hash]);
        }

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
            self::debug($is_class ? $string : $str, $info, 1);
        }
        # self::debug($string, $info);
        if (!function_exists($class)) {
            eval($str);
            #echo $string . PHP_EOL;

        } elseif (self::$debug_exist) {
            $info['info'] = "Cannot redeclare $name()";
            $info['line'] = __LINE__;
            self::debug($is_class ? $string : $str, $info);
        }
    }

    public static function debug($str, $arr, $exit = null)
    {
        echo $str;
        print_r([__LINE__, $arr]);
        if ($exit) {
            exit;
        }
    }

    public static function param($args, $sep = null, $defines = 'defines', $names = 'names', $key_nm = null, $val_nm = null)
    {
        self::$count++;
        $prefix = ($defines && 'defines' != $defines) ? $defines . '_' : '';
        $prefix = ($names && 'names' != $names) ? $names . '_' : $prefix;
        $arr = [];
        $params = [];
        $keys = [];
        $values = [];
        $alphabet = 'abcdefghijklmnopqrstuvwxyz';
        #for ($i = 2; $i < $num; $i++) { $j = $i - 2;$value = func_get_arg($i);
        foreach ($args as $i => $value) {$j = $i;
           
            $str = '';
            
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';

            } elseif (is_null($value)) {
                $value = 'null';

            } elseif(is_string($value)) {
                $value = "'$value'";

            } elseif(is_numeric($value)) {
                
            } elseif(is_array($value)) {            
                # $GLOBALS['FUNC_ARGS'][$name][$j] = $value;

                $nm = "FUNC_ARGS_" . self::$count . "_$j";
                self::$global[] = $nm;
                _define($nm, $value);
                # $value = "\$GLOBALS['FUNC_ARGS']['$name'][$j]";
                $value = "'__CONST__.$nm'";
            } else {
                var_dump($value);
                print_r([__LINE__, __FILE__]);
                exit;
            }
            $key = $prefix . $alphabet[$i];
            $str = '$' . "$key = $value";
            # echo $str . PHP_EOL;
            $arr[] = $str;
            $params[] = "\$$key";
            $keys[] = $key;
            $values[] = $value;
        }
        if ($sep) {
            $arr = implode(', ', $arr); #echo 
            $params = implode(', ', $params);
        }
        $val = [];
        if ($defines) {
            $val[$defines] = $arr;
        }
        if ($names) {
            $val[$names] = $params;
        }
        if ($key_nm) {
            $val[$key_nm] = $keys;
        }
        if ($val_nm) {
            $val[$val_nm] = $values;
        }
        if (self::$prefix) {
            $val[self::$prefix] = $prefix;
        }
        return $val;
    }

    public static function call($name, $args = [])
    {
        $func = $name;
        $pieces = [];
        foreach ($args as $key => $value) {
            $s = "\$args[$key]";
            $pieces[] = $s;
        }
        $arg = implode(', ', $pieces);
        $code = "\$what = \$func($arg);";
        eval($code);
        return $what;
    }

    public static function args($arg = [], $args = [], $param = [], $by_key = null)
    {
        $arr = [];
        $i = 0;
        foreach ($arg as $key => $value) {
            $var_name = $args[$i];
            $val = isset($param[$i]) ? $param[$i] : $value;
            if (is_string($val) && preg_match('/^__CONST__\.(.*)/', $val, $matches)) {
                $val = \Func\_constant($matches[1]);
            }
            $var_name = $by_key ? $var_name : $i;
            $arr[$var_name] = $val;
            $i++;
        }
        return $arr;
    }
}
