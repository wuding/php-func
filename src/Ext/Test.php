<?php

namespace Func\Ext;

class Test
{
	public $name = [];
	public static $cache = [];
	public static $arg = [];
	public static $args = [];

	public function __construct($name = null)
	{
		$this->name = func_get_args();
	}

	public static function _get($name = null)
	{
		self::$arg[$name] = func_get_args();
		return self::$cache[$name];
	}

	public function set($name = null)
	{
		$arg = func_get_args();
		self::$cache[$name] = $arg;
	}

	public function __debugInfo()
	{
		return [
			'name' => $this->name,
			'cache' => self::$cache,
			'arg' => self::$arg,
		];
	}
}