<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore\Driver;

use Nerd\Datastore;

/**
 * APC datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class APC implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * A cached version of the Datstore key
	 *
	 * @var    string
	 */
	protected static $key;

	/**
	 * Magic method called when a class is first encountered by the Autoloader,
	 * providing static initialization.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		self::$key = Datastore::key();
	}

	/**
	 * {@inheritdoc}
	 */
	public function read($key)
	{
		if(($cache = \apc_fetch(self::$key.$key)) !== null)
		{
			return $cache;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function exists($key)
	{
		return ($this->get($key) !== null);
	}

	/**
	 * {@inheritdoc}
	 */
	public function write($key, $value, $minutes = false)
	{
		!is_numeric($minutes) and $minutes = Config::get('datastore.time');
		return (bool) \apc_store(self::$key.$key, $value, $minutes * 60);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($key)
	{
		return (bool) \apc_delete(self::$key.$key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
		apc_clear_cache();
	}
}