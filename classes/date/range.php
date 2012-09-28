<?php

/**
 * Core Nerd library namespace. This namespace contains all of the fundamental
 * components of the Nerd framework, plus additional utilities that are
 * provided by default. Some of these default components have sub namespaces
 * if they provide children objects.
 *
 * @package    Nerd
 * @subpackage Date
 */
namespace Nerd\Date;

// Aliasing rules
use \Nerd\Config;

/**
 * Date Range
 *
 * An traversable object containing multiple Date objects, enabling use of 
 * Nerd's Date class for complex applications. Unlike the other Nerd\Date
 * classes, this one does not extend the standard PHP \DateRange class. The
 * reason for this is that \DateRange is very buggy and unpredictable.
 *
 * @package Nerd
 * @subpackage Date
 */
class Range extends \Nerd\Design\Enumerable
{
	/**
	 * Create a new instance of this class
	 *
	 * @see    Nerd\Range::__construct()
	 */
	public static function make(\Nerd\Date $start, $interval, $end)
	{
		return new static($start, $interval, $end);
	}

	/**
	 * Class Constructor
	 *
	 * @param    Nerd\Date             Start date
	 * @param    string                Interval between dates
	 * @param    Nerd\Date|Integer     End date or number of occurrences
	 * @return   Nerd\Date\Range       Enumerable class
	 */
	public function __construct(\Nerd\Date $start, $interval, $end)
	{
		$this->enumerable = array($start);
		$current = clone $start;

		// If start to end dates are present.
		if(\is_object($end))
		{
			while($current < $end)
			{
				$current->modify($interval);
				$this->enumerable[] = clone $current;
			}
		}

		// If start date and occurrences are present.
		if(\is_integer($end))
		{
			for($i=1; $i < $end; $i++)
			{
				$current->modify($interval);
				$this->enumerable[] = clone $current;
			}
		}
		
		// If invalid, simply return this object
		// with just the start date.
		return $this;
	}
}