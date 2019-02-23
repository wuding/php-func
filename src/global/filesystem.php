<?php

use Func as f;

new \Func\Filesystem;

function path_info($url_path = null , $options = null)
{
	return f\path_info($url_path, $options);
}
