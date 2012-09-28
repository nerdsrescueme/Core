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
 * Exception class
 *
 * The base Nerd exception class provides a place to start throwing Exceptions within
 * the framework. Using this class as the basis for all Nerd exceptions will allow us
 * to catch any framework exception.
 *
 * @package Nerd
 * @subpackage Design
 */
class Exception extends \Exception {}