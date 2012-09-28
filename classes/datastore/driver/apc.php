<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore\Driver;

// Aliasing rules
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
	 * Read all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   string           Returns the contents of the datastore, otherwise null
	 */
	public function read($key)
	{
		if(($cache = \apc_fetch(self::$key.$key)) !== null)
		{
			return $cache;
		}
	}

	/**
	 * Determine whether a datastore key exists
	 *
	 * @param    string           The datastore key to check
	 * @return   boolean          Returns true if the datastore key exists, otherwise false
	 */
	public function exists($key)
	{
		return ($this->get($key) !== null);
	}

	/**
	 * Write data to a datastore key
	 *
	 * @param    string           The datastore key
	 * @param    mixed            The data to be written to the key
	 * @param    integer          The time, in minutes, to store the data. Defaults to the time value in your datastore configuration file
	 * @return   boolean          Returns true if the datastore was successfully written, otherwise false
	 */
	public function write($key, $value, $minutes = false)
	{
		!is_numeric($minutes) and $minutes = Config::get('datastore.time');
		return (bool) \apc_store(self::$key.$key, $value, $minutes * 60);
	}

	/**
	 * Delete all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   boolean          Returns true if the datastore was successfully deleted, otherwise false
	 */
	public function delete($key)
	{
		return (bool) \apc_delete(self::$key.$key);
	}
}

/* End of file apc.php */