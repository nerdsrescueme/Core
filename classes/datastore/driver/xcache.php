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
 * Xcache datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class Xcache implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
{
    use \Nerd\Design\Creational\Singleton;

    /**
     * Magic method called when a class is first encountered by the Autoloader,
     * providing static initialization.
     *
     * @return void No value is returned
     */
    public static function __initialize()
    {
        if (!function_exists('xcache_set')) {
            throw new \RuntimeException('Xcache must be installed to utilize the Xcache datastore driver.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function read($key)
    {
        return xcache_get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return xcache_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function write($key, $value, $minutes = false)
    {
        return xcache_set($key, $value, $minutes);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return xcache_unset($key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        xcache_clear_cache(XC_TYPE_VAR, 0);
    }
}
