<?php

/**
* Source Docblock tag namespace. This namespace is reserved for dockblock
* tags. Tags are used for source code processing instructions and info.
*
* @package Nerd
* @subpackage Source
*/
namespace Nerd\Source\Docblock\Tag;

/**
* Throws Tag Class
*
* The Throws Tag class is use in processing @throws docblock instructions
*
* @package Nerd
* @subpackage Source
* @library Source
*/
class Throws extends \Nerd\Source\Docblock\Tag
{
	/**
	 * Parameter description
	 *
	 * @var    string
	 */
	protected $description;

	/**
	 * Class Constructor
	 *
	 * Parses dockblock tag line
	 *
	 * @param     string     Dockblock tag line
	 * @return    Nerd\Source\Docblock\Tag\Throws
	 */
	public function __construct($tag)
	{
		preg_match('#^@(\w+)\s+([^\s]+)(?:\s+(.*))?#', $tag, $matches);
		
		$this->name = 'throws';
		$this->type = $matches[2];

		if(isset($matches[3]))
		{
			$this->description = preg_replace('#\s+#', ' ', $matches[3]);
		}
	}

	/**
	 * Get this throw tag's description
	 *
	 * @return    string     Paramater description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	public function __toString()
	{
		return "    {$this->type}".PHP_EOL;
	}
}