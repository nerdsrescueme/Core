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
 * Arr class
 *
 * This class provides helper functionality for when dealing with arrays, while
 * also expanding on the native array functionality provided by PHP.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Arr
{
	/**
	 * Determine whether a variable, or multiple variables, is of the array
	 * type. The only difference of this function compared to the native
	 * is_array function is that it allows you to pass multiple arrays.
	 * Additionally, this method allows for instances of \ArrayAccess to be
	 * considered valid arrays.
	 *
	 * ## Usage
	 *
	 *     $return = Arr::is($array1, $array2, $array3);
	 *
	 * @param    mixed            Argument #n of variables to check
	 * @return   boolean          Returns true if all variables passed are arrays, otherwise false
	 */
	public static function is()
	{
		$args = \func_get_args();

		if(!\count($args))
		{
			return false;
		}

		foreach($args as $var)
		{
			if(!(\is_array($var) or ($var instanceof \ArrayAccess)))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Get an item from an array.
	 *
	 * If the specified key is null, the entire array will be returned. The
	 * array may also be accessed using JavaScript "dot" style notation.
	 * Retrieving items nested in multiple arrays is also supported.
	 *
	 * ## Usage
	 *
	 *     Arr::get($myArray, 'parentKey.childKey', 'defaultValue');
	 *
	 * @param    array            The array to be searched
	 * @param    string           The key to retrieve, using "dot" style notation
	 * @param    mixed            A default value to provide if none if found, defaults to null
	 * @param    boolean          In the event a closure is encountered, call it. Defaults to true
	 * @return   mixed            Returns the value of the key, otherwise the default
	 */
	public static function get(array $array, $key, $default = null, $useClosure = true)
	{
		if($key === null)
		{
			return $array;
		}

		$keys = \explode('.', $key);

		foreach($keys as $segment)
		{
			if(!static::is($array) or !isset($array[$segment]))
			{
				return \is_callable($default) ? \call_user_func($default) : $default;
			}

			$array = $array[$segment];
		}

		return (($array instanceof \Closure) and $useClosure) ? \call_user_func($array) : $array;
	}

	/**
	 * Set an array item to a given value.
	 *
	 * This method is primarily helpful for setting the value in an array with
	 * a variable depth, such as configuration arrays.
	 *
	 * If the specified item doesn't exist, it will be created. If the item's
	 * parents do not exist, they will also be created as arrays.
	 *
	 * Like the Arr::get method, JavaScript "dot" notation is supported.
	 *
	 * ## Usage
	 *
	 *     Arr::get($myArray, 'some.random.key', $theValue);
	 *
	 * @param    array            The array to be append
	 * @param    string           The key, or chain of keys to set
	 * @param    mixed            The value to be assigned to the key
	 * @return   void             No value is returned
	 */
	public static function set(array &$array, $key, $value)
	{
		if($key === null)
		{
			return $array = $value;
		}

		$keys = \explode('.', $key);

		while(\count($keys) > 1)
		{
			$key = \array_shift($keys);

			if(!isset($array[$key]) or !static::is($array[$key]))
			{
				$array[$key] = [];
			}

			$array =& $array[$key];
		}

		$array[\array_shift($keys)] = $value;
	}

	/**
	 * Determine if an array key (or keys) exists, with JavaScript "dot"
	 * notation.
	 *
	 * ## Usage
	 *
	 *     $exists = Arr::has($myArray, 'some.key');
	 *
	 * @param    array            The array to search
	 * @param    mixed            The dot-notated key, or an array of keys
	 * @return   boolean          Returns true if the array key (or keys) exist, otherwise false
	 */
	public static function has(array $array, $key)
	{
		foreach(\explode('.', $key) as $key_part)
		{
			if(!static::is($array) or !isset($array[$key_part]))
			{
				return false;
			}

			$array = $array[$key_part];
		}

		return true;
	}

	/**
	 * Unsets an array key, using JavaScript "dot" notation
	 *
	 * ## Usage
	 *
	 *     Arr::delete($myArray, 'some.key');
	 *
	 * @param    string|array     s
	 * @param    array            The array to search
	 * @param    string           The dot-notated key or array of keys
	 * @return   boolean          Returns true if the array key was removed, otherwise false
	 */
	public static function delete(array &$array, $key)
	{
		if(\is_null($key))
		{
			return false;
		}

		if(static::is($key))
		{
			$return = [];

			foreach($key as $k)
			{
				$return[$k] = static::delete($array, $k);
			}

			return $return;
		}

		$key_parts = \explode('.', $key);

		if(!static::is($array) or !isset($array[$key_parts[0]]))
		{
			return false;
		}

		$this_key = \array_shift($key_parts);

		if(!empty($key_parts))
		{
			$key = implode('.', $key_parts);
			return static::delete($array[$this_key], $key);
		}
		else
		{
			unset($array[$this_key]);
		}

		return true;
	}

	/**
	 * Add enumerable functionality to an array
	 *
	 * ## Usage
	 *
	 *     $enum = Arr::toEnumerable($myArray);
	 *
	 * @param    array                      The array to convert
	 * @return   Nerd\Design\Enumerable     The converted array
	 */
	public static function toEnumerable(array $array)
	{
		return new \Nerd\Design\Enumerable($array);
	}
	
	/**
	 * Recursively converts an array data type to an object.
	 *
	 * ## Usage
	 *
	 *     $object = Arr::toObject($myArray);
	 *
	 * @param    array            The array to convert
	 * @return   object           Returns the converted object
	 */
	public static function toObject(array $array)
	{
		return \json_decode(\json_encode((array) $array));
	}
}