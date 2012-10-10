<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore\Driver;

/**
 * Session datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class Session extends Memory implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
{
	/**
	 * Magic method called when a class is first encountered by the Autoloader,
	 * providing static initialization.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		parent::__initialize();

		if (!isset($_SESSION))
		{
			throw new \RuntimeException('$_SESSION must be initialized in order to use the Session based datastore.');
		}

		self::$data = &$_SESSION;
	}
}