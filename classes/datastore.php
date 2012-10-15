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
 * Datastore class
 *
 * The Datastore class enables data caching for key-value-pairs. Multiple
 * options are available for Datastore, and multiple drivers can even be
 * utilized at the same time for appropriate storage.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Datastore extends Design\Creational\SingletonFactory implements Design\Initializable
{
    /**
     * The default driver to be utilized by your application in the event a
     * specific driver isn't called.
     *
     * @var    string
     */
    public static $defaultDriver;

    /**
     * The cached datastore key, from the datastore configuration file
     *
     * @var    string
     */
    protected static $key = false;

    /**
     * Default cache time
     *
     * The default time (in minutes) to cache a value, in the event no time is
     * provided.
     *
     * @var    integer
     */
    protected static $time = false;

    /**
     * Magic method called when a class is first encountered by the Autoloader,
     * providing static initialization.
     *
     * @return void No value is returned
     */
    public static function __initialize()
    {
        if (!\is_dir(\Nerd\STORAGE_PATH) and !\mkdir(\Nerd\STORAGE_PATH)) {
            throw new \RuntimeException('Could not create the STORAGE_PATH ['.\Nerd\STORAGE_PATH.'], please create this directory as a writable path in order to utilize the Datastore');
        }

        static::$defaultDriver = Config::get('datastore.driver', 'file');
    }

    /**
     * The magic call static method is triggered when invoking inaccessible
     * methods in a static context.
     *
     * ## Usage
     *
     * This method exists to allow dynaimc static usage of non-static driver
     * methods.
     *
     * @param    string           The method name being called
     * @param    array            The arguments being passed to the method call
     * @return mixed Returns the value of the intercepted method call
     */
    public static function __callStatic($method, $parameters)
    {
        return call_user_func_array(array(static::instance(), $method), $parameters);
    }

    /**
     * Get the Datastore key, as stored in the datastore configuration file.
     *
     * @return string Returns the datastore key
     */
    public static function key()
    {
        if (static::$key === false) {
            static::$key = Config::get('datastore.key');
        }

        return static::$key;
    }

    /**
     * Get the default time (in minutes) to store a value
     *
     * @return integer Minutes to store the datastore, by default
     */
    public static function time()
    {
        if (static::$time === false) {
            static::$time = Config::get('datastore.time');
        }

        return static::$time;
    }
}
