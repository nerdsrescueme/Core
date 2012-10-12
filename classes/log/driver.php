<?php

/**
 * Log driver namespace. This namespace controls the driver specification for log
 * drivers. Multiple drivers for logging can be loaded at the same time, all of
 * which are singleton references to ensure no additional overhead.
 *
 * @package    Nerd
 * @subpackage Log
 */
namespace Nerd\Log;

use Nerd\Log;

/**
 * Log driver interface
 *
 * This interface defines the driver structure of which functions a driver must
 * implement and how they should be called.
 *
 * @package    Nerd
 * @subpackage Format
 */
abstract class Driver
{
	// Traits
	use \Nerd\Design\Eventable;

	/**
	 * Write a message to the log storage medium
	 *
	 * @param    integer          Level in which to log this error
	 * @param    mixed            Message to write to the database
	 * @return   boolean          Was the log write successful?
	 */
	abstract public function write($level, $message);

	/**
	 * Get x rows from log storage medium
	 *
	 * @param    integer          Number of rows to pull from the log
	 * @return   array            Log entries
	 */
	abstract public function get($rows = null);


	protected function parseLevel($level)
	{
		if (is_string($level))
		{
			$level = strtolower($level);
		}

		if ($level <= Log::DEBUG or $level === 'debug')
		{
			return Log::DEBUG;
		}
		elseif ($level <= Log::INFO or $level === 'info')
		{
			return Log::INFO;
		}
		elseif ($level <= Log::NOTICE or $level === 'notice')
		{
			return Log::NOTICE;
		}
		elseif ($level <= Log::WARNING or $level === 'warning')
		{
			return Log::WARNING;
		}
		elseif ($level <= Log::ERROR or $level === 'error')
		{
			return Log::ERROR;
		}
		elseif ($level <= Log::CRITICAL or $level === 'critical')
		{
			return Log::CRITICAL;
		}
		elseif ($level <= Log::ALERT or $level === 'alert')
		{
			return Log::ALERT;
		}
		elseif ($level <= Log::EMERGENCY or $level === 'emergency')
		{
			return Log::EMERGENCY;
		}

		return false;
	}

	/**
	 * Convert any valid PHP type to a string
	 *
	 * @param    mixed          PHP value to convert
	 * @return   string|false   String representation of message, or false
	 */
	protected function stringify($message)
	{
		$type = strtolower(gettype($message));

		switch ($type)
		{
			case 'boolean' :
				return $message ? 'True' : 'False';
				break;
			case 'integer' :
				return (string) $message;
				break;
			case 'double' :
			case 'float' :
				return (string) $message;
				break;
			case 'string' :
				return $message;
				break;
			case 'array' :
				return print_r($message, true);
				break;
			case 'object' :
				return var_export($message, true);
				break;
			case 'resource' :
				return 'Resource: '.get_resource_type($message);
			case 'null' :
			default :
				return false;
				
		}
	}
}