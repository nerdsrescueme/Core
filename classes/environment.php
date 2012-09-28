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
 * Environment class
 *
 * The environment provides convenient detection and classification to allow the
 * use of environmental circumstantial modification.
 *
 * ## Usage
 *
 *     $environment = Environment::$active;
 *     $mode        = Environment::$mode;
 *
 * @package    Nerd
 * @subpackage Core
 */
class Environment implements Design\Initializable
{
	// Environment modes
	const MODE_DEVELOPMENT = 'development';
	const MODE_TESTING     = 'testing';
	const MODE_STAGING     = 'staging';
	const MODE_PRODUCTION  = 'production';
	const MODE_CLI         = 'cli';

	/**
	 * The active environment identifier
	 *
	 * @var    string
	 */
	public static $active;

	/**
	 * The active environment mode
	 *
	 * @var    integer
	 */
	public static $mode;

	/**
	 * Initialize the Environment class
	 *
	 * During initialization three scenarios are tested for. If no match is found, an
	 * exception thrown indicating that an unrecognized environment is running this
	 * Nerd installation. Following are the three situations tested:
	 *
	 * 1. Is Nerd running in CLI mode? Presumably be Geek, Nerd's built in command
	 *    line runner tool.
	 * 2. Is the environment explicitly set in the application configuration file?
	 * 3. Can the environment be discovered by cascading through all of the
	 *    environments defined in the application configuration file?
	 * 
	 * @throw \RuntimeException if no environment could be found.
	 * 
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		// Test for CLI
		if (PHP_SAPI === 'cli')
		{
			static::$active = 'cli';
			static::$mode = static::MODE_CLI;
			return;
		}

		if (($environment = Config::get('application.environment')) !== null)
		{
			static::$active = $environment;
			static::$mode   = Config::get("environment.$environment.mode");
			return;
		}

		foreach(Config::get('environment', []) as $env => $vars)
		{
			if (static::test($vars['field'], (array) $vars['identifier']))
			{
				static::$active = $env;
				static::$mode   = $vars['mode'];
				return;
			}
		}

		throw new \RuntimeException('You are running Nerd in an unfamiliar environment. Please check the environments configuration file and make adjustments if needed.');
	}

	/**
	 * Test a $_SERVER field against a given identifier
	 *
	 * When environment is being determined it is done so by comparing a variable
	 * within the $_SERVER superglobal versus a value (or set of values) defined in
	 * the environments configuration file. The test method performs the comparison
	 * between the field and identifier(s) based on whether or not the identifier
	 * exists in the field.
	 *
	 * @param    string          Value to test against
	 * @param    array           Array of values to test for
	 * @returns  boolean
	 */
	private static function test($field, array $identifier)
	{
		$identifier = (array) $identifier;

		foreach ($identifier as $id)
		{
			if (strpos($_SERVER[$field], $id) !== false)
			{
				return true;
			}
		}

		return false;
	}
}