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
 * Serializable interface
 *
 * @package    Nerd
 * @subpackage Core
 */
interface Serializable extends \JsonSerializable
{
	/**
	 * Return an array of object data to be formatted
	 *
	 * @return array
	 */
	public function __sleep();
}