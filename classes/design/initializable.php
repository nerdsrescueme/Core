<?php

/**
 * Design namespace. This namespace is meant to provide abstract concepts and in
 * most cases, simply interfaces that in someway structures the general design
 * used in core components. Additionally, the Design namespace provides sub
 * namespaces that relate specifically to common design patterns that can be
 * attached to classes without duplication of functionality.
 *
 * @package Nerd
 * @subpackage Design
 */
namespace Nerd\Design;

/**
 * Initializable interface
 *
 * This interface identifies classes that should be statically initialized
 * during Autoloading (class file inclusion).
 *
 * ## Usage
 *
 * To use the Initializable interface, simply implement it into your class, and
 * provide a `::__initialize()` method to be called by the Autoloader.
 *
 *     class MyClass implements \Nerd\Design\Initializable
 *     {
 *     	public static function __initialize()
 *     	{
 *     		// whatever you want to do during initialization
 *     	}
 *     }
 *
 * __Note:__ You should *NEVER* call `::__initialize()` in your code, unless
 * done specifically by the class that implemented the method. This will prevent
 * unneccessary duplication of the initialization process.
 *
 * @see        https://bugs.php.net/bug.php?id=60098
 * @package    Nerd
 * @subpackage Design
 */
interface Initializable
{
	/**
	 * Magic method called when a class is first encountered by the Autoloader,
	 * providing static initialization.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize();
}