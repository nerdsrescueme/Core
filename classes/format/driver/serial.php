<?php

/**
 * Format driver namespace. This namespace controls the format driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Format
 */
namespace Nerd\Format\Driver;

/**
 * Serialization format driver class
 *
 * The driver handles the formatting of serialized data
 *
 * @package    Nerd
 * @subpackage Format
 */
class Serial implements \Nerd\Format\Driver
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * Converts a raw data value into its array equivelant
	 *
	 * @param    string           The raw data to convert
	 * @param    integer          Flags relavent to the formatting
	 * @return   array|boolean    The array equivelant of the raw data, otherwise false on failure
	 * @throws   \InvalidArgumentException   Throws an InvalidArgumentException if $data is not a string, or the correct format of data
	 */
	public function from($data, $flags = null)
	{
		return unserialize(trim($data));
	}

	/**
	 * Converts an array into its raw data value equivelant
	 *
	 * @param    array           The array to convert
	 * @param    integer         Flags relavent to the formatting
	 * @return   string|boolean  The raw data equivelant of the array, otherwise false on failure
	 * @throws   \InvalidArgumentException   Throws an InvalidArgumentException if $data is not an array
	 */
	public function to($data, $flags = null)
	{
		return serialize(trim($data));
	}
}