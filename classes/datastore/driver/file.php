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
use Nerd\Config;
use Nerd\File as F;
use Nerd\Str;

/**
 * File datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class File implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * The path to write to
	 *
	 * @var    string
	 */
	public static $path = \Nerd\STORAGE_PATH;

	/**
	 * Magic method called when a class is first encountered by the Autoloader,
	 * providing static initialization.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		self::$path = \Nerd\STORAGE_PATH.DS.'datastore'.DS.Datastore::key();
	}

	/**
	 * Read all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   string           Returns the contents of the datastore, otherwise null
	 */
	public function read($key)
	{
		if(!F::exists(self::$path.$key))
		{
			return null;
		}

		// File based caches store have the expiration timestamp stored in
		// UNIX format prepended to their contents. This timestamp is then
		// extracted and removed when the cache is read to determine if the file
		// is still valid
		if(time() >= Str::sub($cache = F::get(self::$path.$key), 0, 10))
		{
			$this->delete($key);
			return null;
		}

		return unserialize(substr($cache, 10));
	}

	/**
	 * Determine whether a datastore key exists
	 *
	 * @param    string           The datastore key to check
	 * @return   boolean          Returns true if the datastore key exists, otherwise false
	 */
	public function exists($key)
	{
		return F::exists(self::$path.$key);
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
		$value = (time() + ($minutes * 60)).serialize($value);

		if(!F::exists(self::$path.$key))
		{
			return F::create(self::$path.$key, $value);
		}
		else
		{
			return (bool) F::put(self::$path.$key, $value);
		}
	}

	/**
	 * Delete all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   boolean          Returns true if the datastore was successfully deleted, otherwise false
	 */
	public function delete($key)
	{
		return F::delete(self::$path.$key);
	}

	/**
	 * Reset the file path to the default location.
	 *
	 * @return    null
	 */
	public function resetPath()
	{
		static::$path = \Nerd\STORAGE_PATH;
	}
	
	/**
	 * Set the file path to a new location
	 *
	 * @param    string           Location to set the datastore path to
	 * @return   null
	 */
	public function setPath($to)
	{
		static::$path = $to;
	}
}