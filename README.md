# php-func

PHP 通用函数库及别名定义，按需加载



## Install

```sh
composer require wuding/php-func
```



## Usage

### func

func - 定义函数别名



#### 说明

```php
func ( mixed $function_config [, array $alias_classes = array() [ , mixed $... ]] ) : void
```

单独或批量定义函数别名，并预设参数默认值



#### 参数

**function_config**

要调用或声明的原始函数（名），也可以是批量配置

**alias_classes**

要定义的函数别名或依赖哪些类

**...**

函数的参数



#### 范例

bootstrap.php


```php
$Composer = require APP_PATH . '/../vendor/autoload.php';

$functions = [
    '_isset' => ['', [], '', null],
    '\Func\array_diff_kv' => ['', [], [], [], false],
    '\Func\Arr::diff' => ['\Func\diff', [], [], [], false],
    'str_match' => ['', '//', '', null, false],
    'wtf' => [function ($text) { return $text; }, 'hi'],
];

func($functions, ['Variable', 'Arr', 'PCRE']);
func('\Func\Str\unicode_decode', '', [], [], [], false);
new \Func\Alias('test', function ($text) { return $text; }, 'test');

echo str_match('/^\d+元/', '3元', '1角', true);
echo unicode_decode('\u65b0\u6d6a\u5fae\u535a', 'json');
echo test('str');
```

