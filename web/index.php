<?php

// 错误报告
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_NAMES = array(
	'' => 'index',
	'index',
	'str',
);


$Composer = require __DIR__ . '/../vendor/autoload.php';
$functions = [
    '_isset' => ['', [], '', null],
    #'\Func\array_diff_kv' => ['', [], [], [], false],
    'str_match' => ['', '//', '', null, false],
    #'\Func\Arr\arr_fixed_assoc' => ['', [], false],
    #'arr_reset_values',
];
func($functions, ['variable', 'arr', 'pcre', 'Filesystem']);




arr_fixed_assoc($_NAMES, true);
arr_reset_values($_NAMES, ['prefix' => __DIR__ .  '/../example/', 'suffix' => '.php'], true);
$basename = \Func\path_info(0, PATHINFO_BASENAME);
#print_r([__LINE__, get_defined_functions()['user'], get_included_files(), $_NAMES, $basename, $_NAMES[$basename]]);
if (array_key_exists($basename, $_NAMES) && include $_NAMES[$basename]) {
	//
} else {
	include $_NAMES[''];
}
/**/
# print_r([__LINE__, get_defined_constants(), get_defined_vars()]);
