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
 * Enumerable Class
 *
 * The enumerable class is meant to provide more functionality to array's and
 * objects in PHP. It can be used directly, or by child classes to implement
 * flexible and powerful array testing and manipulation methods.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Enumerable implements \Countable, \ArrayAccess, \Iterator {

	/**
	 * Static Constructor
	 *
	 * @param    array    Array for enumeration
	 * @return   void
	 */
	public static function construct(array $enumerable = [])
	{
		return new static($enumerable);
	}

	/**
	 * Array set for enumeration
	 *
	 * @var    array
	 */
	protected $enumerable;

	/**
	 * Class Constructor
	 *
	 * @param    array    Array for enumeration
	 * @return   void
	 */
	public function __construct(array $enumerable = [])
	{
		$this->enumerable = $enumerable;
	}

	/**
	 * Get an array of accepted values, with the acceptance based off of
	 * the given lambda's return boolean value.
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    array       Array of accepted values
	 */
	public function accept(callable $lambda)
	{
		$accept = [];

		foreach($this as $value)
		{
			if($lambda($value))
			{
				$accept []= $value;
			}
		}

		return $accept;
	}

	/**
	 * Evaluate enum to see if ANY values match against the lambda.
	 *
	 * Example lambda, checks for numeric values:
	 * <code>
	 *     function($value) { return is_numeric($value); }
	 * </code>
	 *
	 * @param    callable     Used to evaluate enum elements, returns boolean
	 * @return   boolean     True if ANY match is found.
	 */
	public function any(callable $lambda)
	{
		foreach($this as $value)
		{
			if($lambda($value))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * Evaluate enum to see if ALL values match against the lambda.
	 *
	 * Example lambda, checks for numeric values:
	 * <code>
	 *     function($value) { return is_numeric($value); }
	 * </code>
	 *
	 * @param    callable     Used to evaluate enum elements, returns boolean
	 * @return   boolean     True if ALL values match
	 */
	public function all(callable $lambda)
	{
		foreach($this as $value)
		{
			if(!$lambda($value))
			{
				return false;
			}
		}
		
		return true;
	}

	/**
	 * Get an array that corresponds to enum, with boolean values depending
	 * on how lambda evaluated against the value.
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    array        Array of boolean values
	 */
	public function collect(callable $lambda)
	{
		return array_map($lambda, $this->enumerable);
	}

	/**
	 * Convert all enum values into a single value.
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    mixed
	 */
	public function combine(callable $lambda)
	{
		$shift  = array_shift($this->enumerable);
		$result = array_reduce($this->enumerable, $lambda, $shift);
		array_unshift($this->enumerable, $shift);
		
		return $result;
	}

	/**
	 * Perform lambda against each enum value.
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    void
	 */
	public function each(callable $lambda)
	{
		array_walk($this->enumerable, $lambda);
	}

	/**
	 * Perform lambda against each enum value, include the enum value's index
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    void
	 */
	public function eachWithIndex(callable $lambda)
	{
		foreach($this as $index => $value)
		{
			$lambda($index, $value);
		}
	}

	/**
	 * Find ONE element based on lambda evaluation. The first value to match
	 * will be returned. If no values match, the default value is returned.
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @param     mixed        Default value to return
	 * @return    mixed
	 */
	public function find(callable $lambda, $default = null)
	{
		foreach($this as $value)
		{
			if($lambda($value))
			{
				return $value;
			}
		}

		return $default;
	}

	/**
	 * Find ONE element based on lambda evaluation. The first value to match
	 * will be returned. This will search recursively based on the $recurse
	 * parameter.
	 *
	 * @param     boolean      Used to evaluate enum values, returns boolean
	 * @param     mixed        Tests for recursion, returns value or false
	 * @param     mixed        Recursed array, always set to null on first call
	 * @return    mixed        Matched value
	 */
	public function findRecursively(callable $lambda, callable $recurse, array $array = null)
	{
		$array === null and $array = $this->toArray();

		while (count($array) >= 1)
		{
			if ($value = array_shift($array) and $nvalue = $recurse($value))
			{
				if ($return = $this->findRecursively($lambda, $recurse, $nvalue))
				{
					return $return;
				}
				continue;
			}

			if ($lambda($value))
			{
				return $value;
			}
		}

		return null;
	}

	/**
	 * Find ALL values based on lambda evaluation.
	 *
	 * @param     boolean      Used to evaluate enum values, returns boolean
	 * @return    array        Matching values
	 */
	public function findAll(callable $lambda)
	{
		return array_values(array_filter($this->enumerable, $lambda));
	}

	/**
	 * Find ALL values based on lambda evaluation. This will recursively search based on
	 * a second function that tests for recursion.
	 *
	 * @param    boolean     Used to evaluate enum values, returns boolean
	 * @param    mixed       Tests for recursion, returns the value tested or false
	 * @param    array       Recursed array, always set to null for first call
	 * @param    array       Matched values
	 */     
	public function findAllRecursively(callable $lambda, callable $recurse, array $array = null)
	{
		$return = [];
		$array === null and $array = $this->toArray();

		while (count($array) >= 1)
		{
			if ($value = array_shift($array) and $nvalue = $recurse($value))
			{
				$return = $return + $this->findAllRecursively($lambda, $recurse, $nvalue);
				continue;
			}

			$lambda($value) and $return[] = $value;
		}

		return $return;
	}

	/**
	 * Run a grep search against enum values
	 *
	 * @param    string     Grep regular expression
	 * @return   array      Array of matched values
	 */
	public function grep($pattern, callable $lambda = null)
	{
		$match = preg_grep($pattern, $this->enumerable);
		
		return ($lambda === null) ? $match : array_map($lambda, $match);
	}

	/**
	 * Group enum values by the return result of lambda
	 *
	 * @param    callable     Return a value to group values
	 * @return   array        Grouped array
	 */
	public function group(callable $lambda)
	{
		$result = [];

		foreach($this as $value)
		{
			$v = $lambda($value);
			$result[$v][] = $value;
		}

		return $result;
	}

	/**
	 * Return a specific index matching a lamda
	 *
	 * @param    callable    Used to evaluate enum values, returns boolean
	 * @return   mixed       Index of discovered value
	 */
	public function index(callable $lamda = null)
	{
		$indexes = $this->indexes($lamda);
		return array_shift($indexes);
	}

	/**
	 * Return indexes matching lamda
	 *
	 * @param    callable     Used to evaluate enum values, returns boolean
	 * @return   array        Array of indexes for discovered values
	 */
	public function indexes(callable $lamda = null)
	{
		if ($lamda === null)
		{
			return array_keys($this->enumerable);
		}

		$result = [];

		foreach ($this as $index => $value)
		{
			$lamda($value) and $result[] = $index;
		}

		return $result;
	}

	/**
	 * Return the highest value in the enumerable array
	 *
	 * @see       http://us.php.net/manual/en/function.max.php
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    mixed        Highest value in enumerable array
	 */
	public function max(callable $lambda = null)
	{
		return $lambda === null ? max($this->enumerable) : end($this->sort($lambda));
	}

	/**
	 * Check for an object's existence within the enum values. "Object" in this
	 * context means any value or construct that can be tested for equality in PHP.
	 *
	 * @param    mixed       Object to search for
	 * @return   boolean     True if $obj was found within enum values
	 */
	public function member($obj)
	{
		return in_array($obj, $this->enumerable);
	}

	/**
	 * Return the lowest value in the enumerable array
	 *
	 * @see       http://us.php.net/manual/en/function.min.php
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    mixed       Lowest value in enumerable array
	 */
	public function min(callable $lambda = null)
	{
		return $lambda === null ? min($this->enumerable) : current($this->sort($lambda));
	}

	/**
	 * Get an array of rejected values, with the rejection based off of
	 * the given lambda's return boolean value.
	 *
	 * @param     callable     Used to evaluate enum values, returns boolean
	 * @return    array       Array of rejected values
	 */
	public function reject(callable $lambda)
	{
		$reject = [];

		foreach($this as $value)
		{
			if(!$lambda($value))
			{
				$reject []= $value;
			}
		}

		return $reject;
	}

	/**
	 * Return a reversed enum array
	 *
	 * @return    array     Reversed array
	 */
	public function reverse()
	{
		return array_reverse($this->enumerable);
	}

	/**
	 * Preform lambda on each member of a reversed enum array
	 *
	 * @param     callable     Function to perform on each enum value
	 * @return    void
	 */
	public function reverseEach(callable $lambda)
	{
		$reversed = array_reverse($this->enumerable);
		array_walk($reversed, $lambda);
	}

	/**
	 * Preform lambda on each key, value of a reversed enum array
	 *
	 * @param     callable     Function to perform on each enum value
	 * @return    void
	 */
	public function reverseEachWithIndex(callable $lambda)
	{
		$reversed = array_reverse($this->enumerable, true);

		foreach($reversed as $key => $value)
		{
			$lambda($key, $value);
		}
	}

	/**
	 * Sort the enum array by default sort rules or lambda results
	 *
	 * @param     callable     Used to evaluate enum values, returns -1, 0 or 1
	 * @return    array        Sorted enum array
	 */
	public function sort(callable $lambda = null)
	{
		$sort = $this->enumerable;

		return ($lambda === null) ? sort($sort) : sort($sort, $lambda);
	}

	/**
	 *
	 */
	public function sort_by(callable $lambda)
	{
		foreach($this as $value)
		{
			$sort[$value] = $lambda($value);
		}

		asort($sort);

		return array_values(array_flip($sort));
	}

	/**
	 * Return Enumerable instance as an array
	 *
	 * @return    array     Enumerable array
	 */
	public function toArray()
	{
		return $this->enumerable;
	}

	/**
	 * Return Enumerable instance as an object
	 *
	 * @return    object     Enumerable array converted to an object
	 */
	public function toObject()
	{
		return \Nerd\Arr::toObject($this->enumerable);
	}

	/**
	 * ArrayAccess methods
	 */
	public function offsetExists($offset) { return isset($this->enumerable[$offset]); }
	public function offsetGet($offset) { return $this->enumerable[$offset]; }
	public function offsetSet($offset, $value) { $this->enumerable[$offset] = $value; }
	public function offsetUnset($offset) { unset($this->enumerable[$offset]); }

	/**
	 * Countable methods
	 */
	public function count() { return count($this->enumerable); }

	/**
	 * Iterator methods
	 */
	public function current() { return current($this->enumerable); }
	public function key() { return key($this->enumerable); }
	public function next() { return next($this->enumerable); }
	public function rewind() { return reset($this->enumerable); }
	public function valid() { return key($this->enumerable) !== null; }
}