<?php

namespace Func;

class Filesystem
{
	public function __construct()
	{

	}
}

function path_info($url_path = null , $options = null)
{
	$url_path = $url_path ? : $_SERVER['REQUEST_URI'];
	$url_path = parse_url($url_path, PHP_URL_PATH);
	return pathinfo($url_path, $options);
}
