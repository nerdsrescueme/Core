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
 * Extendable class for making objects traversable.
 *
 * @package Nerd
 * @subpackage Core
 */
class Iterable implements \Iterator {

	/**
	 * Array for object traversal
	 *
	 * @var    array
	 */
	public $iterator = [];

	/**
	 * Position of the array pointer
	 *
	 * @var    integer
	 */
	public $position = 0;

	//
	// The following methods are for object traversal
	// @see    http://www.php.net/manual/en/class.iterator.php
	//

	public function current()
	{
		return $this->iterator[$this->position];
	}

	public function key()
	{
		return $this->position;
	}

	public function next()
	{
		$this->position++;
	}

	public function rewind()
	{
		$this->position = 0;
	}

	public function valid()
	{
		return isset($this->iterator[$this->position]);
	}
}