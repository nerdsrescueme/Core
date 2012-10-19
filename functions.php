<?php

if (!function_exists('import')) {
	function import() {
		$path = func_get_args() and array_unshift($path, \Nerd\LIBRARY_PATH);
		return include join(DS, $path);
	}
}