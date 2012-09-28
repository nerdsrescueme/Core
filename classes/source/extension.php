<?php

/**
* Nerd Source namespace. This namespace contains all of the components
* of the Nerd Source library. Source is a library designed to give
* developers access to their source code within the context of the
* Nerd framework.
*
* @package Nerd
* @subpackage Source
*/
namespace Nerd\Source;

// Aliasing rules
use \Nerd\Design\Collection;

/**
* Source Extension Class
*
* The purpose of this class is to create a usable representation of an
* extension using PHP's Reflection API.
*
* @package Nerd
* @subpackage Source
*/
class Extension extends \ReflectionExtension {

	/**
	 * Enumerable array of extension functions
	 *
	 * @var    Nerd\Design\Collection
	 */
	protected $functions;

	/**
	 * Enumerable array of extension classes
	 *
	 * @var    Nerd\Design\Collection
	 */
	protected $classes;

	/**
	 * Get all this extensions functions
	 *
	 * @return    Nerd\Design\Collection     Enumerable array of extension functions
	 */
	public function getFunctions()
	{
		if($this->functions === null)
		{
			$functions = parent::getFunctions();

			foreach($functions as $key => $function)
			{
				$functions[$key] = new Funktion($function->getName());
			}

			$this->functions = new Collection($functions);
		}

		return $this->functions;
	}

	/**
	 * Get all this extensions classes
	 *
	 * @return    Nerd\Design\Collection     Enumerable array of extension classes
	 */
	public function getClasses()
	{
		if($this->classes === null)
		{
			$classes = parent::getClasses();

			foreach($classes as $key => $class)
			{
				$classes[$key] = new Klass($class->getName());
			}

			$this->classes = new Collection($classes);
		}

		return $this->classes;
	}
}