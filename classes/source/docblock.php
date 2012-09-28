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
* Source Docblock Class
*
* The Nerd Source Docblock class provides docblock parsing in a way
* that's similar to PHP's Reflection API.
*
* @package    Nerd
* @subpackage Source
*/
class Docblock implements \Reflector {

	/**
	 * Unaltered docblock content
	 *
	 * @var    string
	 */
	protected $raw;

	/**
	 * Cleaned docblock content
	 *
	 * @var    string
	 */
	protected $cleaned;

	/**
	 * Enumerable array containing parsed @tags
	 *
	 * @var    Nerd\Design\Enumerable
	 */
	protected $tags;

	/**
	 * Brief description parsed from the docblock
	 *
	 * @var    string
	 */
	protected $shortDescription;

	/**
	 * Extended description parsed from the docblock
	 *
	 * @var    string
	 */
	protected $longDescription = '';

	/**
	 * Starting line number of docblock
	 *
	 * @var    integer
	 */
	protected $startLine;

	/**
	 * Ending line number of docblock
	 *
	 * @var    integer
	 */
	protected $endLine;

	/**
	 * Class Constructor
	 *
	 * Initializes this class instance to allow for parsing the given
	 * docblock content.
	 *
	 * @param     string     Docblock to be parsed
	 * @return    $this
	 */
	public function __construct($reflector)
	{
		if($reflector instanceof \Reflector)
		{
			$docblock        = $reflector->getDocComment();
			$this->startLine = $reflector->getStartLine() - substr_count($docblock, "\n") - 1;
			$this->endLine   = $reflector->getStartLine() - 1;
		}
		else
		{
			$docblock = $reflector;
		}

		if($docblock == '')
		{
			$docblock = "/**\n* Empty Docblock\n*/";
		}

		$this->tags = new Collection();
		$this->raw = $docblock;
		$this->parse();
	}

	/**
	 * Get the cleaned content from this docblock
	 *
	 * @return    string    Cleaned docblock content
	 */
	public function getContents()
	{
		return $this->cleaned;
	}

	/**
	 * Get the last line of this docblock
	 *
	 * @return    integer    Last line of docblock content
	 */
	public function getEndLine()
	{
		return $this->endLine;
	}

	/**
	 * Get the long description from parsed docblock
	 *
	 * @return    string     Extended description from docblock content
	 */
	public function getLongDescription()
	{
		return $this->longDescription;
	}

	/**
	 * Get the raw docblock content
	 *
	 * @return    string     Unparsed docblock content
	 */
	public function getRawContents()
	{
		return $this->raw;
	}

	/**
	 * Get the brief description from parsed docblock
	 *
	 * @return    string     Brief description from docblock content
	 */
	public function getShortDescription()
	{
		return $this->shortDescription;
	}

	/**
	 * Get the first line number of this docblock
	 *
	 * @return    integer    First line of docblock content
	 */
	public function getStartLine()
	{
		return $this->startLine;
	}

	/**
	 * Get all tags with a given name
	 *
	 * @param     string     Tag name to return
	 * @return    array      All tags with given name
	 * @return    array      Empty array if no tags are found
	 */
	public function getTag($name)
	{
		return $this->tags->findAll(function($tag) use ($name)
		{
			return $tag->getName() == $name;
		});
	}

	/**
	 * Check if a given tag exists in this docblock instance
	 *
	 * @param     string     Tag name to check for
	 * @return    boolean    True if tag exists
	 * @return    boolean    False if tag does not exist
	 */
	public function hasTag($name)
	{
		return $this->tags->any(function($tag) use ($name)
		{
			return $tag->getName() == $name;
		});
	}

	/**
	 * Get all tags present on this docblock instance
	 *
	 * @return    Nerd\Design\Enumerable     Enumerable array containing all @tags
	 */
	public function getTags()
	{
		return $this->tags;
	}

	/**
	 * Parse the given docblock
	 *
	 * This method expects the docblock to be formatted as JavaDoc compliant. It will
	 * fail consistently with any other docblock formatting paradighm.
	 *
	 * @return     void
	 */
	protected function parse()
	{
		// Clean up the docblock, remove beginning *'s and declarations
		$docblock = $this->raw;
		$docblock = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docblock);
		$docblock = ltrim($docblock, "\r\n");

		$this->cleaned = $docblock;

		$lines = explode("\n", $docblock);
		$blankFound = false;
		$paramFound = false;

		foreach($lines as $line)
		{
			// Check for the first blank line and parameter 
			!$blankFound and $blankFound = ($line == '');
			!$paramFound and $paramFound = (substr($line, 0, 1) == '@');

			// If the line is empty and both a blank and param have been
			// found skip this line, we don't want blank lines for params
			if($line == '' and $blankFound and $paramFound)
			{
				continue;
			}

			// If no blank or param have been found we must be inside the
			// short description.
			if(!$blankFound and !$paramFound)
			{
				$this->shortDescription = trim($line);
				continue;
			}

			// If we have found a blank but no param we must be inside the
			// extended description
			if($blankFound and !$paramFound)
			{
				$this->longDescription .= $line.PHP_EOL;
				continue;
			}

			// If we've found both a blank and a param we are inside of the
			// @params
			if($blankFound and $paramFound)
			{
				$this->tags->add(Docblock\Tag::factory($line));
				continue;
			}
		}

	}

	/**
	 * Export the dockblock into Reflection style string
	 *
	 * @todo    Implement export across Source library
	 */
	public static function export()
	{
		// Implement me
	}

	/**
	 * Convert this classes information to a string equivalent
	 *
	 * @credit     This method is taken from Zend_Reflection_Docblock
	 * @return     string     String equivalent of this class
	 */
	public function __toString()
	{
		$str  = 'Docblock [ /* Docblock */ ] {'.PHP_EOL.PHP_EOL;
		$str .= "  - Tags [{$this->tags->count()} {".PHP_EOL;

		$this->tags->each(function($tag) use(&$str)
		{
			$str .= (string) $tag;
		})

		$str .= '  }'.PHP_EOL;
		$str .= '}'.PHP_EOL;

		return $str;
	}
}