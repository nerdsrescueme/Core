<?php

/**
 * Format driver namespace. This namespace controls the driver specification for
 * format drivers. Multiple drivers for formatting can be loaded at the same
 * time, all of which are singleton references to ensure no additional overhead.
 *
 * @package    Nerd
 * @subpackage Format
 */
namespace Nerd\Format;

/**
 * Format driver interface
 *
 * This interface defines the driver structure of which functions a driver must
 * implement and how they should be called.
 *
 * @package    Nerd
 * @subpackage Format
 */
interface Driver
{
	/**
	 * Converts a raw data value into its array equivelant
	 *
	 * @param    string           The raw data to convert
	 * @param    integer          Flags relavent to the formatting
	 * @return   array|boolean    The array equivelant of the raw data, otherwise false on failure
	 * @throws   \InvalidArgumentException   Throws an InvalidArgumentException if $data is not a string, or the correct format of data
	 */
	public function to($data, $flags = null);

	/**
	 * Converts an array into its raw data value equivelant
	 *
	 * @param    array           The array to convert
	 * @param    integer         Flags relavent to the formatting
	 * @return   string|boolean  The raw data equivelant of the array, otherwise false on failure
	 * @throws   \InvalidArgumentException   Throws an InvalidArgumentException if $data is not an array
	 */
	public function from($data, $flags = null);
}

/* End of file driver.php */