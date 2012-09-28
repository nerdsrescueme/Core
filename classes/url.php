<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * URL Class
 *
 * The URL class provides the means to dissect, create and manipulate full URL's.
 *
 * @package Nerd
 * @subpackage Core
 */
class Url
{
	// Traits
	use \Nerd\Design\Creational\Factory;

	/**
	 * The current application URL
	 *
	 * @var string
	 */
	public static $current;

	/**
	 * Create an application URL.
	 *
	 * @todo Create more complex detection
	 *
	 * @param    string          The URI to append
	 * @return   string          Compiled URL
	 */
	public static function construct($path)
	{
		return static::site($path)->render();
	}

	public static function current()
	{
		if (static::$current === null)
		{
			static::$current = static::instance('current');
		}

		return static::$current;
	}
}