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
 * Factory pattern trait
 *
 * The factory method pattern is an object-oriented design pattern to implement
 * the concept of factories. Like other creational patterns, it deals with the
 * problem of creating objects (products) without specifying the exact class
 * that will be created. The creation of an object often requires complex
 * processes not appropriate to include within the composing object. The
 * object's creation may lead to a significant duplication of code, may require
 * information not accessible to the composing object, may not provide a
 * sufficient level of abstraction, or may otherwise not be part of the
 * composing object's concerns. The factory method design pattern handles these
 * problems by defining a separate method for creating the objects, which
 * subclasses can then override the specific derived type of product that will
 * be created.
 *
 * Some of the processes required in the creation of an object include
 * determining which object to create, managing the lifetime of the object, and
 * managing specialized build-up and tear-down concerns of the object. Outside
 * the scope of design patterns, the term **factory method** can also refer to a
 * method of factory whose main purpose is creation of objects.
 *
 * __Note:__ Some classes may opt to not require the developer to specify a
 * driver during the Factory::instance() call. If they wish to specify a
 * default, the class must provide a `public static $defaultDriver` which will
 * be read during the `::instance()` call.
 *
 * __Note:__ Some derived classes may prefer to use some other variation of the
 * word driver (for example, gateway). If they wish to use a different form of
 * wording for it, they can specify a `public static $factoryType`, where the
 * keyword is used as the directory under the parent class.
 *
 * ## Usage
 *
 * To implement the factory method pattern into a class, simply include it:
 *
 *     class MyClass
 *     {
 *         use \Nerd\Design\Creational\Factory;
 *     }
 *
 * After you've created the parent class, you need to create at least one driver
 * type that can be loaded by the `::instance()` method, following this pattern:
 *
 *     namespace MyClass\Driver;
 *     
 *     class Mydriver
 *     {
 *     }
 *
 * Once the driver is available, you can call the `::instance()` method to
 * create an instance of that driver.
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
 * @package    Nerd
 * @subpackage Design
 */
trait Factory
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
	 * Construct a new instance to the factory driver, and pass along any
	 * additionally provided arguments that were defined.
	 *
	 * @param    string           The factory driver to be loaded, otherwise `$defaultDriver` will be utilized if available
	 * @param    mixed            Argument #n to be passed along to the driver
	 * @return   object           An instance to the appropriate driver, determined by this factory
	 * @throws   \InvalidArgumentException   This exception is thrown when a driver was not specified, and no `$defaultDriver` is available
	 * @throws   \OutOfBoundsException       This exception is thrown when the specified $driver, or $defaultDriver, does not exist and cannot be instantiated
	 */
	final public static function instance($driver = null)
	{
		$class = get_called_class();
		$args  = func_get_args() and array_shift($args);

		if($driver === null)
		{
			if(static::$defaultDriver === null)
			{
				throw new \InvalidArgumentException('A $driver was not specified during '.$class.'::instance(), and no $defaultDriver is available. Please specify the driver you wish to use');
			}

			$driver = static::$defaultDriver;
		}

		try
		{
			$instance = '\\'.$class.'\\'.\ucfirst(static::$factoryType).'\\'.\ucfirst($driver);
			$instance = new \ReflectionClass($instance);
			$instance = $instance->newInstanceArgs($args);
		}
		catch(\ReflectionException $e)
		{
			throw new \OutOfBoundsException($class.' driver type ['.$driver.'] cannot be instanciated, ensure the driver exists and has a __construct method.');
		}

		return $instance;
	}

	/**
	 * Magic Static Caller
	 *
	 * This method enables convenient access to drivers as static methods, providing
	 * better syntax.
	 */
	public static function __callStatic($driver, $parameters)
	{
		$class = get_called_class() and array_unshift($parameters, $driver);
		return forward_static_call_array([$class, 'instance'], $parameters);
	}
}