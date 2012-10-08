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
 * Dotparser trait
 *
 * The dot parser trait provides classes with the ability to parse javascript's "dot
 * notation" in order to provide convenient access to different resources.
 *
 * @package Nerd
 * @subpackage Core
 */
trait Dotparser
{
	/**
	 * Parse a configuration key.
	 *
	 * The value on the left side of the dot is the configuration file name,
	 * while the right side of the dot is the item within that file.
	 *
	 * @param     string
	 * @return    array
	 */
	private static function parse($key)
	{
		$package = (strpos($key, '::') !== false) ? substr($key, 0, strpos($key, ':')) : \Nerd\APPLICATION_NS;

		if($package !== \Nerd\APPLICATION_NS)
		{
			$key = substr($key, strpos($key, ':') + 2);
		}

		$key = (count($segments = explode('.', $key)) > 1) ? implode('.', array_slice($segments, 1)) : null;

		return [$package, $segments[0], $key];
	}
}