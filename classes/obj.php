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
 * Obj class
 *
 * This class provides helper functionality when working with objects
 *
 * @package    Nerd
 * @subpackage Core
 */
class Obj
{
	/**
	 * Determine whether a variable, or multiple variables, is of the object
	 * type. The only difference of this function compared to the native type
	 * is_object function is that it allows you to pass multiple variables.
	 *
	 * ## Usage
	 *
	 *     $return = Obj::is($object1, $object2, $object3);
	 *
	 * @param    mixed            Argument #n of variables to check
	 * @return   boolean          Returns true if all variables passed are objects, otherwise false
	 */
	public static function is()
	{
		$args = func_get_args();

		if(!count($args))
		{
			return false;
		}

		foreach($args as $var)
		{
			if(!is_object($var))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Recursively converts an object to an associative array
	 *
	 * ## Usage
	 *
	 *     $array = Obj::toArray($object);
	 *
	 * @param    array            The object to convert
	 * @return   object           Returns the object as a converted array
	 */
	public static function toArray($object)
	{
		if(!is_object($object))
		{
			throw new \InvalidArgumentException('The first parameter of Obj::toArray must be an object');
		}

		return json_decode(json_encode($object), true);
	}
}