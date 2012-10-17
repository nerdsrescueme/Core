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
* Return Tag Class
*
* The Returns Tag class is use in processing @return and @returns docblock instructions
*
* @package Nerd
* @subpackage Source
* @library Source
*/
class Returns extends \Nerd\Source\Docblock\Tag
{
    /**
     * Class Constructor
     *
     * Parses dockblock tag line
     *
     * @param     string     Dockblock tag line
     * @return Nerd\Source\Docblock\Tag\Returns
     */
    public function __construct($tag)
    {
        preg_match('#^@(\w+)\s+([^\s]+)(?:\s+(.*))?#', $tag, $matches);

        $this->name = 'return';

        // Automatically alias namespaced class paths to (@see Namespace\Class) format so it
        // can be picked up by Source::replaceTags()
        $this->type = (strpos($matches[2], '\\') !== false) ? '(@see '.$matches[2].')' : $matches[2];

        if (isset($matches[3])) {
            $this->description = preg_replace('#\s+#', ' ', $matches[3]);
        }
    }
}
