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
 * Multiton pattern abstract class
 *
 * The Multiton pattern is a design pattern similar to the singleton, which
 * allows only one instance of a class to be created. The multiton pattern
 * extends on the singleton concept to manage a map of named instances as
 * key-value pairs.
 *
 * Rather than have a singleton instance *per application* the multiton pattern
 * instead ensures a single instance *per key*
 *
 * Most people and textbooks consider this a singleton pattern. For example,
 * multiton does not explicity appear in the highly-regarded object-oriented
 * programming text book __Design Patterns__ (it appears as a more flexible
 * approach named *Registry of Singletons*).
 *
 * __Note:__ Some classes may opt to not require the developer to specify a
 * key during the Multiton::instance() call. If they wish to specify a default,
 * the class must provide a `public static $defaultKey` which will be read
 * during the `::instance()` call.
 *
 * ## Usage
 *
 * To implement the multiton pattern into a class, simply extend the pattern
 * with your class:
 *
 *     class MyClass extends \Nerd\Design\Creational\Multiton
 *     {
 *     }
 *
 * Once the class is available, you can refer to a Multiton instance of the
 * class by providing `::instance('key')`. Each time the same key is referred
 * to, the same class will be returned, thus providing you with multiple
 * singleton instances.
 *
 * __Note:__ Some classes may opt to not require the developer to specify a key
 * during the Multiton::instance() call. If they wish to specify a default, the
 * class must provide a `public static $defaultKey` which will be read during
 * the `::instance()` call.
 *
 * @package    Nerd
 * @subpackage Design
 */
trait Multiton
{
    /**
     * Registered instances
     *
     * @var    array
     */
    private static $instances = [];

    /**
     * A default key to provide, in the event non is specified. Defaults to
     * null.
     *
     * @var    string
     */
    public static $defaultKey;

    /**
     * Construct a new object instance and assigns it to a unique key. In the
     * event the key has already been assigned, the same object is returned,
     * regardless of context.
     *
     * @param    string           The instance key
     * @return object                    The singleton instance to the object
     * @throws \InvalidArgumentException This exception is thrown when a key was not specified, and no `$defaultKey` is available
     */
    final public static function instance($key = null)
    {
        $class = get_called_class();

        if ($key === null) {
            if (static::$defaultKey === null) {
                throw new \InvalidArgumentException('A $key was not specified during '.$class.'::instance(), and no $defaultKey is available. Please specify the driver you wish to use');
            }

            $key = static::$defaultKey;
        }

        if (!isset(self::$instances[$key])) {
            self::$instances[$key] = new static;
        }

        return self::$instances[$key];
    }

    // Disables the ability to construct the object, use `::instance()` instead
    final protected function __construct() {}

    // Disables the ability to clone the object, use `::instance()` instead
    final protected function __clone() {}
}
