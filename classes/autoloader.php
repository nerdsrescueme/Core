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
 * Autoloader class
 *
 * The Autoloader class provides a simplified approach to autoloading classes,
 * while still retaining PSR-0 technical interoperability. With the exception of
 * itself, this Autoloader serves as the primary class loader throughout the
 * library.
 *
 * ## Usage
 *
 * The Autoloader is automatically registered to the SPL autoload stack by the
 * bootstrap process. Assuming you include that file, this class will work
 * transparently, and you don't need to worry about any of the functions!
 *
 * @package Nerd
 * @subpackage Core
 */
class Autoloader
{
	/**
	 * Adds the Autoloader to the SPL autoloader stack
	 *
	 * @return   boolean          Returns true on success, otherwise false
	 */
	public static function register($prepend = false)
	{
		return spl_autoload_register('Nerd\Autoloader::load', true, $prepend);
	}

	/**
	 * Removes the Autoloader from the SPL autoloader stack.
	 *
	 * @return   boolean          Returns true on success, otherwise false
	 */
	public static function unregister()
	{
		return spl_autoload_unregister('Nerd\Autoloader::load');
	}

	/**
	 * Removes the namespace from a given class string, leaving you with just the
	 * class name without its namespace.
	 *
	 * @param    string           Full class name with namespace
	 * @return   string           Class name without namespace
	 */
	public static function denamespace($class)
	{
		$class = explode('\\', $class);
		return array_pop($class);
	}

	/**
	 * Autoloads a class, interface or trait. When called by the SPL autoload stack,
	 * the failure of this method results in an exception being thrown.
	 *
	 * @param    string           The class or interface
	 * @return   boolean          Returns true if the class was loaded, otherwise false
	 */
	public static function load($name)
	{
		if(static::exists($name))
		{
			return true;
		}

		$class = str_replace('_', '\\', $name);
		$namespace = DS;

		if(($position = strpos($class, '\\')) !== false)
		{
			$namespace .= strtolower(substr($class, 0, $position)).DS;
			$class      = substr($class, ($position + 1));
		}

		$path = \Nerd\LIBRARY_PATH.$namespace.'classes'.DS.strtolower(trim(str_replace('\\', DS, $class))).'.php';

		if(!is_file($path))
		{
			return false;
		}

		include $path;

		if(!static::exists($name))
		{
			return false;
		}

		if(($ifaces = class_implements($name, true)) !== false and isset($ifaces['Nerd\Design\Initializable']))
		{
			call_user_func($name.'::__initialize');
		}

		return true;
	}

	/**
	 * Check whether a class or interface exists, without attempting to autoload
	 * it.
	 *
	 * @param    string           The class or interface
	 * @return   boolean          Returns true if the class or interface exists, otherwise false
	 */
	public static function exists($name)
	{
		return class_exists($name, false) or interface_exists($name, false);
	}
}