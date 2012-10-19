<?php

/**
 * Creational design pattern namespace.
 *
 * Creational design patterns are design patterns that deal with object creation
 * mechanisms, trying to cream objects in a manner suitable to the situation.
 * The basic form of object creation could result in design problems or added
 * complexity to the design. Creational design patterns solve this problem by
 * somehow controlling this object creation. Creational design patterns can be
 * further categoriezed into Object-creational and Class-creational patterns.
 * Where, Object-creational patterns deal with Object creation and
 * Class-creational deal with Class-initialization.
 *
 * @package    Nerd
 * @subpackage Design
 */
namespace Nerd\Design\Creational;

/**
 * Singleton Factory abstract pattern class
 *
 * The Singleton Factory patttern is a combination of the singleton and factory
 * patterns, allowing for singleton instances of factory drivers.
 *
 * This pattern is similar to that of the Multiton pattern as well, except in
 * regards that the multiple singleton instances are actually that of the
 * factory drivers, instead of multiple singleton instances of the parent class
 * implementing the pattern.
 *
 * ## Usage
 *
 * To implement the singleton factory method pattern into a class, simply extend
 * the pattern with your class:
 *
 *     class MyClass extends \Nerd\Design\Creational\SingletonFactory
 *     {
 *     }
 *
 * After you've created the parent class, you need to create at least one
 * singleton driver type that can be loaded by the `::instance()` method,
 * following this pattern:
 *
 *     namespace MyClass\Driver;
 *
 *     class Mydriver extends \Nerd\Design\Creational\Singleton
 *     {
 *     }
 *
 * Once the driver is available, you can call the `::instance()` method to
 * create an instance of that driver, which will return the Singleton instance
 * to the driver.
 *
 *     $instance = MyClass::instance('mydriver');
 *
 * __Note:__ To follow the factory method specification, the first character of
 * a driver's class name must be uppercase. Additional uppercase letters in the
 * class name must be specified during the `::instance()` call if you choose
 * to use them. It is highly recommended to leave drivers all lowercase, except
 * for the first letter.
 *
 *     // Assuming the class name is 'MyDriver'
 *     $instance = MyClass::instance('MyDriver'); // Instancing becomes much more annoying
 *     $instance = MyClass::instance('myDriver'); // The first letter could also be lowercase
 *
 *     // Assuming the class name is 'Mydriver', this is a lot easier to work with. Highly recommended!
 *     $instance = MyClass::instance('mydriver');
 *
 * @see        \Nerd\Design\Creational\Factory
 * @see        \Nerd\Design\Creational\Singleton
 * @package    Nerd
 * @subpackage Design
 */
abstract class SingletonFactory
{
    /**
     * The factory driver verb to utilize, defaults to 'Driver'
     *
     * @var    string
     */
    public static $factoryType = 'driver';

    /**
     * A default driver to provide, in the event non is specified. Defaults to
     * null.
     *
     * @var    string
     */
    public static $defaultDriver;

    /**
     * Registered instances
     *
     * @var    array
     */
    private static $instances = [];

    /**
     * Has this instance been initialized?
     *
     * @var boolean
     */
    public static $initialized = false;

    /**
     * Constructs a new instance to the factory driver, and passed along any
     * additional arguments that were defined.
     *
     * @param    string           The factory type to be loaded
     * @param    mixed            Argument #n to be passed along to the driver
     * @return object An instance to the appropriate driver, determined through factory
     */
    public static function instance($driver = null)
    {
        $class = get_called_class();
        $args  = func_get_args() and array_shift($args);

        if ($driver === null) {
            if (static::$defaultDriver === null) {
                throw new \InvalidArgumentException('A $driver was not specified during '.$class.'::instance(), and no $defaultDriver is available. Please specify the driver you wish to use');
            }

            $driver = static::$defaultDriver;
        }

        // If the driver used is namespaced, do not use the
        // default folder to resolve the path.
        if (strpos($driver, '\\') !== false) {
            $instance = $driver;
        } else {
            $instance = $class.'\\'.ucfirst(static::$factoryType).'\\'.ucfirst($driver);
        }

        $key = str_replace('\\', '_', $class);

        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = [];
        }

        if (!isset(self::$instances[$key][$driver])) {
            if (!class_exists($instance)) {
                throw new \OutOfBoundsException($instance.' class does not exist, and cannot be loaded through the SingletonFactory.');
            } elseif ($instance instanceof \Nerd\Design\Creational\Singleton) {
                throw new \OutOfBoundsException('The '.$instance.' class could not be loaded, as it does not follow the SingletonFactory access layer specification');
            }

            self::$instances[$key][$driver] = forward_static_call_array($instance.'::instance', $args);
        }

        return self::$instances[$key][$driver];
    }
}
