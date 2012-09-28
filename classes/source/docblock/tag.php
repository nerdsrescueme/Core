<?php

/**
* Nerd Source's docblock namespace. This namespace is reserved for elements
* that are contained within a docblock object
*
* @package    Nerd
* @subpackage Source
* @library    Source
*/
namespace Nerd\Source\Docblock;

/**
* Tag Class
* 
* The Tag class acts as both a starting point for docblock tags and
* a basic tag class. If no subclassed tag exists within the pre-
* defined tags array, an instance of this class will be returned.
*
* @todo       Use (@see Nerd\Config) to register predefined tags
* @package    Nerd
* @subpackage Source
* @library    Source
*/
class Tag {

	/**
	 * Registered subclass tags
	 *
	 * @var    array
	 */
	public static $predefinedTags = array(
		'throws'  => '\\Nerd\\Source\\Docblock\\Tag\\Throws',
		'param'   => '\\Nerd\\Source\\Docblock\\Tag\\Param',
		'return'  => '\\Nerd\\Source\\Docblock\\Tag\\Returns',
		'returns' => '\\Nerd\\Source\\Docblock\\Tag\\Returns',
	);

	/**
	 * Tag name
	 *
	 * @var    string
	 */
	protected $name;

	/**
	 * Parameter exception type
	 *
	 * @var    string
	 */
	protected $type;

	/**
	 * Tag description
	 *
	 * @var    string
	 */
	protected $description;

	/**
	 * Static Class Factory
	 *
	 * Figures out if a pre-defined tag is being requested, if so it
	 * returns an instance of that tag. Otherwise, it returns an instance
	 * of this class.
	 *
	 * @param     string     Single line @tag from docblock
	 * @return    Nerd\Source\Docblock\Tag
	 */
	public static function factory($tag)
	{
		// Strips excess whitespace and parses.
		preg_match('#^@(\w+)(\s|$)#', preg_replace('!\s+!', ' ', $tag), $matches);
		
		if(isset(static::$predefinedTags[$matches[1]]))
		{
			$class = static::$predefinedTags[$matches[1]];
			return new $class($tag);
		}

		return new static($tag);
	}

	/**
	 * Add a tag to the predefined tag classes array
	 *
	 * When a tag needs special processing outside of what this class
	 * can provide, it requires a separate subclass of this class. It
	 * must be registered with this class so the factory method can
	 * return the proper subclass.
	 *
	 * @param     string     Name of tag
	 * @param     string     Fully namespaced subclass, must extend (@see Nerd\Source\Docblock\Tag)
	 * @return    void
	 */
	public static function registerTagClass($tagName, $tagClass)
	{
		static::$predefinedTags[$tagName] = $tagClass;
	}

	/**
	 * Class Constructor
	 *
	 * Parses given tag data and returns an instance of this class.
	 *
	 * @param     string     Single line @tag from docblock
	 * @return    Nerd\Source\Docblock\Tag
	 */
	public function __construct($tag)
	{
		preg_match('#^@(\w+)(?:\s+([^\s].*)|$)?#', $tag, $matches);

		$this->name = $matches[1];

		if (isset($matches[2]) && $matches[2])
		{
			$this->description = $matches[2];
		}
	}

	/**
	 * Get this tags name
	 *
	 * @return     string     Tag name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Get this tags variable type
	 *
	 * @return    string     Parameter variable type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Get this tags description
	 *
	 * @return    string     Tag description
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Export the dockblock tag into Reflection style string
	 *
	 * @todo Implement export across Source library
	 */
	public static function export()
	{
		// Implement me
	}

	/**
	 * Convert this tags information to a string equivalent
	 *
	 * @credit This method is taken from Zend_Reflection_Docblock_Tag
	 * @return string String equivalent of this tag
	 */
	public function __toString()
	{
		return 'Docblock Tag [ * @'.$this->_name.' ]'.PHP_EOL;
	}
}