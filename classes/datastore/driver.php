<?php

/**
 * Datastore namespace. This namespace controls the driver specification for
 * datastore drivers. Multiple drivers for datastores can be loaded at the same
 * time, all of which are singleton references to ensure no additional overhead.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore;

/**
 * Datastore driver interface
 *
 * The Datastore driver outlines the available methods that should exist in
 * individual datastore drivers. This provides consistent usage throughout
 * drivers, without having to worry about specific dependencies.
 *
 * @pacakge    Nerd
 * @subpackage Datastore
 */
interface Driver
{
	/**
	 * Read all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   string           Returns the contents of the datastore, otherwise null
	 */
	public function read($key);

	/**
	 * Determine whether a datastore key exists
	 *
	 * @param    string           The datastore key to check
	 * @return   boolean          Returns true if the datastore key exists, otherwise false
	 */
	public function exists($key);

	/**
	 * Write data to a datastore key
	 *
	 * @param    string           The datastore key
	 * @param    mixed            The data to be written to the key
	 * @param    integer          The time, in minutes, to store the data. Defaults to the time value in your datastore configuration file
	 * @return   boolean          Returns true if the datastore was successfully written, otherwise false
	 */
	public function write($key, $value, $minutes = false);

	/**
	 * Delete all data from a datastore key
	 *
	 * @param    string           The datastore key
	 * @return   boolean          Returns true if the datastore was successfully deleted, otherwise false
	 */
	public function delete($key);
}

/* End of file driver.php */