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
class Datastore extends Design\Creational\SingletonFactory
{
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
