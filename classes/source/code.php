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
use \Nerd\Design\Enumerable as Enum;

/**
* Source Code class
*
* The Source Code class represents the actual source code a file contains
* line by line. This class reads file data and converts it to usable data
* in the most useful ways possible.
*
* @package Nerd
* @subpackage Source
*/
class Code {

	/**
	 * Enumerable array containing lines of source code
	 *
	 * @var    Nerd\Design\Enumerable
	 */
	protected $source;

	/**
	 * Class Constructor
	 *
	 * Takes an absolute file path and converts it into an array of
	 * the files lines of code. The 
	 *
	 * @param     string     Absolute path to a source file
	 * @return    $this
	 */
	public function __construct($file)
	{
		if(!$lines = file($file))
		{
			throw new \OutOfBoundsException('File ['.$file.'] could not be found.');
		}

		$this->source = new Enum($lines);
	}
}