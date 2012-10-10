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
use Nerd\Datastore
  , Nerd\Arr;

/**
 * Memory datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class Memory implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * A cached version of the Datstore key
	 *
	 * @var    string
	 */
	protected static $key;

	/**
	 * In memory data
	 *
	 * @var array
	 */
	protected static $data = [];

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
		return Arr::get(self::$data, $key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function exists($key)
	{
		return Arr::has(self::$data, $key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function write($key, $value, $minutes = false)
	{
		return Arr::set(self::$data, $key, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete($key)
	{
		return Arr::delete(self::$data, $key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function flush()
	{
		self::$data = [];
	}
}