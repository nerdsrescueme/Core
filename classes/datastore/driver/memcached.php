<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Atom
 * @subpackage Datastore
 */
namespace Atom\Datastore\Driver;

// Aliasing rules
use Atom\Datastore;
use Atom\Memcached as M;

/**
 * Memcached datastore driver class
 *
 * @package    Atom
 * @subpackage Datastore
 */
class Memcached implements \Atom\Datastore\Driver, \Atom\Design\Initializable
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * A cached version of the Datstore key
	 *
	 * @var    string
	 */
	protected static $key;

	/**
	 * The Memcached instance
	 *
	 * @var    Memcached
	 */
	protected static $memcached;

	/**
	 * Magic method called when a class is first encountered by the Autoloader,
	 * providing static initialization.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		self::$memcached = M::instance();
		self::$key       = Datastore::key();
	}

	/**
	 * Read all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   string           Returns the contents of the datastore, otherwise null
	 */
	public function read($key)
	{
		if(($cache = self::$memcached->get(self::$key.$key)) !== false)
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
		return (!is_null($this->get($key)));
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
		self::$memcached->set(self::$key.$key, $value, 0, ($minutes * 60));
	}

	/**
	 * Delete all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   boolean          Returns true if the datastore was successfully deleted, otherwise false
	 */
	public function delete($key)
	{
		self::$memcached->delete(self::$key.$key);
	}
}

/* End of file memcached.php */