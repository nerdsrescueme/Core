<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Config class
 *
 * Provides methods for working with configuration items residing within
 * configuration files that return keyed arrays.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Config
{
	// Traits
	use Design\Dotparser;

	/**
	 * All of the loaded configuration items
	 *
	 * @var    array
	 */
	private static $items = [];

	/**
	 * Get a config item.
	 *
	 * Configuration items are retrieved using "dot" notation. So, asking for
	 * the "application.timezone" configuration item would return the "timezone"
	 * option from the "application" configuration file.
	 *
	 * If the name of a configuration file is passed without specifying an item,
	 * the entire configuration array will be returned.
	 *
	 * ## Usage
	 *
	 *     Config::get('file.key', $default_value);
	 *
	 * @param    string           The "dot" notation key to retrieve
	 * @param    string           A default value to return if no value is provided
	 * @param    boolean          In the event a closure is encountered, call it. Defaults to true
	 * @return   mixed            Returns the value of the key or configuration set requested, otherwise $default
	 */
	public static function get($key, $default = null, $closure = true)
	{
		list($package, $file, $key) = static::parse($key);

		if(!static::load($package, $file))
		{
			return is_callable($default) ? call_user_func($default) : $default;
		}

		if($key === null)
		{
			return static::$items[$package][$file];
		}

		return Arr::get(static::$items[$package][$file], $key, $default, $closure);
	}

	/**
	 * Set a configuration item
	 *
	 * Similar to the Config::get method, this function uses "dot" notation to
	 * allow for setting of configuration items. Setting a configuration item does
	 * not save the configuration permanently, only for the life of this request.
	 *
	 * ## Usage
	 *
	 *     Config::set('file.key', $value);
	 *
	 * @param    string           The "dot" notation key to be set
	 * @param    mixed            The value of the key to be set
	 * @return   void             No value is returned
	 */
	public static function set($key, $value)
	{
		list($package, $file, $key) = static::parse($key);

		if(!static::load($package, $file))
		{
			throw new \OutOfBoundsException("Error setting configuration option. Configuration file [$file] is not defined.");
		}

		Arr::set(static::$items[$package][$file], $key, $value);
	}

	/**
	 * Load all of the configuration items from a file.
	 *
	 * Nerd supports environment specific configuration files. So, the base
	 * configuration array will always be loaded first, and then any environment
	 * specific options will be merged in later.
	 *
	 * @param     string
	 * @param     string
	 * @return    boolean
	 */
	private static function load($package, $file)
	{
		if(isset(static::$items[$package]) and isset(static::$items[$package][$file]))
		{
			return true;
		}

		// Load in the default (nerd) configurations, if one exists. Once that
		// is loaded, we can merge the modules configuration options into the
		// base array. This allows for the convenient cascading of configuration
		// options.
		$path   = join(DS, [\Nerd\LIBRARY_PATH, 'nerd', 'config']);
		$config = (file_exists($path = $path.DS.$file.'.php') ? include $path : []);

		if($package !== 'nerd')
		{
			$path = join(DS, [\Nerd\LIBRARY_PATH, $package, 'config', "$file.php"]);
			if(file_exists($path))
			{
				$config = array_merge($config, include $path);
			}
		}

		if(count($config) > 0)
		{
			static::$items[$package][$file] = $config;
		}

		return isset(static::$items[$package][$file]);
	}
}