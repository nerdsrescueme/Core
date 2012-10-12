<?php

/**
 * Log driver namespace. This namespace controls the driver specification for log
 * drivers. Multiple drivers for logging can be loaded at the same time, all of
 * which are singleton references to ensure no additional overhead.
 *
 * @package Nerd
 * @subpackage Log
 */
namespace Nerd\Datastore\Driver;

use Nerd\Log;

/**
 * Void log driver class. Any data passed into this log storage will be immediately
 * sent into the computing netherworld.
 *
 * @package Nerd
 * @subpackage Log
 */
class Void implements \Nerd\Log\Driver
{
	// Traits
	use \Nerd\Design\Creational\Singleton;

	/**
	 * {@inheritdoc}
	 */
	public function write($level, $message)
	{
		return true;
	}
}