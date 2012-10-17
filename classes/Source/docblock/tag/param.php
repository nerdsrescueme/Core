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
* Param Tag Class
*
* The Param Tag class is use in processing @param docblock instructions
*
* @package Nerd
* @subpackage Source
* @library Source
*/
class Param extends \Nerd\Source\Docblock\Tag
{
    /**
     * Parameter variable type
     *
     * @var string
     */
    protected $type;

    /**
     * Param variable name
     *
     * @var string
     */
    protected $variableName;

    /**
     * Class Constructor
     *
     * Parses dockblock tag line
     *
     * @param     string     Dockblock tag line
     * @return Nerd\Source\Docblock\Tag\Param
     */
    public function __construct($tag)
    {
        preg_match('#^@(\w+)\s+([^\s]+)(?:\s+(\$\S+))?(?:\s+(.*))?#s', $tag, $matches);

        $this->name = 'param';

        // Automatically alias namespaced class paths to (@see Namespace\Class) format so it
        // can be picked up by Source::replaceTags()
        $this->type = (strpos($matches[2], '\\') !== false) ? '(@see '.$matches[2].')' : $matches[2];

        if (isset($matches[3])) {
            $this->variableName = $matches[3];
        }

        if (isset($matches[4])) {
            $this->description = preg_replace('#\s+#', ' ', $matches[4]);
        }
    }

    /**
     * Get this tags variable name
     *
     * @return string Parameter variable name
     */
    public function getVariableName()
    {
        return $this->variableName;
    }
}
