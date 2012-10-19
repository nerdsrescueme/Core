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
 * Singleton pattern trait
 *
 * The singleton pattern is a design pattern used to implement the mathematical
 * concept of a singleton, by restricting the instantiation of a class to one
 * object. This is useful when exactly one object is needed to coordinate
 * actions across the system. This concept is sometimes generalized to systems
 * that operate more efficiently when only one object exists, or that restrict
 * the instanciation to a certain number of objects.
 *
 * ## Usage
 *
 * To implement the singleton pattern into a class, simply include it:
 *
 *     class MyClass
 *     {
 *         use \Nerd\Design\Creational\Singleton;
 *     }
 *
 * Once the class is available, you can refer to its singleton instance with the
 * `::instance()` method. This will always return the same instance of the
 * class, thus your singleton is complete!
 *
 * @package    Nerd
 * @subpackage Design
 */
trait Singleton
{
    /**
     * Registered instances
     *
     * @var object
     */
    private static $instance;


    /**
     * Construct a new object instance. In the event an object has previously
     * been initialized, the previous instance will be returned.
     *
     * @return object The singleton instance to the object
     */
    final public static function instance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    // Disables the ability to construct the object, use `::instance()` instead
    final protected function __construct() {}

    // Disables the ability to clone the object, use `::instance()` instead
    final protected function __clone() {}
}
