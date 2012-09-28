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
* Source Funktion Class
*
* The Funktion Class is named Funktion because function is a reserved word in PHP.
* The purpose of this class is to create a usable representation of a
* function using PHP's Reflection API.
*
* @package Nerd
* @subpackage Source
*/
class Funktion extends \ReflectionFunction
{
	use \Nerd\Source\Traits\Docblock;

	protected $parameters;

	/**
	* Get the function's source code as a string
	*
	* @todo Use (@see Nerd\Source\File) to render this function content.
	* @param boolean Include the docblock with the source code?
	* @return string Class source code
	*/
	public function getContents($include_docblock = true)
	{
		$lines = file($this->getFileName());

		return implode('', array_splice(
			$lines, 
			$this->getStartLine($include_docblock), 
			($this->getEndLine() - $this->getStartLine()), 
			true
		));
	}

	/**
	* Get this functions ($see Nerd\Source\Paramater) array
	*
	* @param boolean Return paramaters as a string?
	* @return Nerd\Design\Enumerable Enumerable array of (@see Nerd\Source\Paramater)s
	* @return string String representation of the params
	*/
	public function getParameters($as_string = false)
	{
		$params = parent::getParameters();

		foreach($params as $key => $param)
		{
			$params[$key] = new Parameter(array($this->getDeclaringClass()->getName(), $this->getName()), $param->getName());
		}

		if($as_string)
		{
			$return = '';

			foreach($params as $param)
			{
				$return .= '$'.$param->getName();

				if($param->isDefaultValueAvailable())
				{
					$return .= '='.\Nerd\Source::convertValue($param->getDefaultValue());
				}

				$return .= ', ';
			}

			return trim($return, ', ');
		}

		return new Collection($params);
	}

	/**
	* Get the starting line of this function
	*
	* @param    boolean    Start from the docblock?
	* @return   integer    Starting line number for this class
	*/
	public function getStartLine($include_docblock = false)
	{
		return ($include_docblock) ? $this->getDocblock()->getStartLine() : parent::getStartLine();
	}
}