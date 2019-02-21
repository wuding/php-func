<?php

$Composer = require APP_PATH . '/../vendor/autoload.php';

// 批量配置
$functions = [
    '_isset' => ['', [], '', null], // 没有命名空间的，缺省使用 Func
    '\Func\array_diff_kv' => ['', [], [], [], false], // 主命名空间和函数名
    '\Func\Arr::diff' => ['\Func\diff', [], [], [], false], // 使用静态方法，配置第一项是要用的别名
    'str_match' => ['', '//', '', null, false], // 配置第二项级以后是函数参数默认值
    'wtf' => [function ($text) { return $text; }, 'hi'], // 直接定义匿名函数
    '\OpenSearch\Suggestions::ls' => [['ls', [['A'], 'B', 'C']], [], 'q', 'http'], // 第一项如果是数组，就可以把对象构造参数默认值写上
];
func($functions, ['Variable', 'Arr', 'PCRE']); // 第二个是需要加载的类

// 单独定义
func('\Func\Str\unicode_decode', '', [], [], [], false); // 依次是：调用的类与函数、别名、参数默认值
new \Func\Alias('test', function ($text) { return $text; }, 'test'); // 别名位置可以是函数
new \Func\Alias('\OpenSearch\Suggestions->test', ['lst', [['a'], 'b', 'c']], [], 'query', 'https'); // 也可以是别名与对象创建参数默认值


// 函数别名用法
echo _isset($_GET, 'q', 'query_string');
print_r(array_diff_kv(['test' => 'text'], ['test' => 'string']));
print_r(\Func\diff(['test' => 'text'], ['test' => 'string']));
echo str_match('/^\d+元/', '3元', '1角', true);
echo wtf('what the fuck');
echo unicode_decode('\u65b0\u6d6a\u5fae\u535a', 'json');
echo test('str');


// 对象函数使用方法
$lst = new lst(['`'], '!', '@'); // 新建一个对象操作类
print_r($lst);
echo $lst;
print_r($lst::ls(['+'], '-', '*'));
print_r($lst->test(['func'], 'ti', 'on'));

print_r(ls(['0'], '1', '2')); // 使用定义的函数
print_r($ls = ls::init(['z'], 'y', 'x')); // 初始化原对象并返回

print_r(ls::func('test', '->')); // 重定义默认方法
print_r($l = ls::func(true, ['func'], 'ti', 'on', 's')); // 重定义参数默认值，返回操作类
print_r($l::run(['j'], 'k', 'l', 'm')); // 总是用预定义的方法，并返回原对象
print_r($l(['^'], '|', '$')->test('<', '>')); // 初始化参数，并调用方法
print_r($l->test('t', 'e')); // 只使用方法

