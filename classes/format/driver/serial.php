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
 * @package    Nerd
 * @subpackage Format
 */
class Serial implements \Nerd\Format\Driver
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * {@inheritdoc}
	 */
	public function from($data, $flags = null)
	{
		return unserialize(trim($data));
	}

	/**
	 * {@inheritdoc}
	 */
	public function to($data, $flags = null)
	{
		return serialize(trim($data));
	}
}