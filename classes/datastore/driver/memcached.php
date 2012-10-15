<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore\Driver;

use Nerd\Datastore
  , Nerd\Memcached as M;

/**
 * Memcached datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class Memcached implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
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
     * @return void No value is returned
     */
    public static function __initialize()
    {
        self::$memcached = M::instance();
        self::$key       = Datastore::key();
    }

    /**
     * {@inheritdoc}
     */
    public function read($key)
    {
        if (($cache = self::$memcached->get(self::$key.$key)) !== false) {
            return $cache;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return (!is_null($this->get($key)));
    }

    /**
     * {@inheritdoc}
     */
    public function write($key, $value, $minutes = false)
    {
        !is_numeric($minutes) and $minutes = Config::get('datastore.time');
        self::$memcached->set(self::$key.$key, $value, 0, ($minutes * 60));
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        self::$memcached->delete(self::$key.$key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        self::$memcached->flush();
    }
}
