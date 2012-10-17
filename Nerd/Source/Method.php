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
* Source Method Class
*
* The Nerd Source Method class is meant to provide a usable interface
* for working with the PHP Reflection API within Nerd.
*
* @package Nerd
* @subpackage Source
*/
class Method extends \ReflectionMethod
{
    use \Nerd\Source\Traits\Docblock
      , \Nerd\Source\Traits\ParentClass;

    /**
     * Methods paramaters
     *
     * @var    Nerd\Design\Collection
     */
    protected $parameters;

    /**
     * Get the entire method source code as a string
     *
     * @todo     Use (@see Nerd\Source\File) to render this methods content.
     * @param    boolean     Include the docblock with the source code?
     * @return string Class source code
     */
    public function getContents($include_docblock = false)
    {
        $lines = file($this->getDeclaringClass()->getFileName());

        return implode('', array_splice(
            $lines,
            $this->getStartLine($include_docblock) - 1,
            $this->getEndLine() - $this->getStartLine($include_docblock) + 1,
            true
        ));
    }

    /**
     * Check if this class is a subclass of a given class
     *
     * @param    string     Class to compare against this class
     * @return boolean True if this class matches the comparison class
     */
    public function isInheritedFrom($class)
    {
        return $class == $this->class;
    }

    /**
     * Get this methods ($see Nerd\Source\Paramater) array
     *
     * @param    boolean                    Return paramaters as a string?
     * @return Nerd\Design\Enumerable Enumerable array of (@see Nerd\Source\Paramater)s
     * @return string                 String representation of the params
     */
    public function getParameters($as_string = false)
    {
        if ($this->parameters === null) {
            $params = parent::getParameters();

            foreach ($params as $key => $param) {
                $params[$key] = new Parameter(array($this->getDeclaringClass()->getName(), $this->getName()), $param->getName());
            }

            $this->parameters = new Collection($params);
        }

        if ($as_string) {
            $return = '';

            foreach ($this->parameters as $param) {
                $return .= '$'.$param->getName();

                if ($param->isDefaultValueAvailable()) {
                    $return .= '='.\Nerd\Source::convertValue($param->getDefaultValue());
                }

                $return .= ', ';
            }

            return trim($return, ', ');
        }

        return $this->parameters;
    }

    /**
     * Get the starting line of this method
     *
     * @param    boolean     Start from the docblock?
     * @return integer Starting line number for this class
     */
    public function getStartLine($include_docblock = false)
    {
        return ($include_docblock) ? $this->getDocblock()->getStartLine() : parent::getStartLine();
    }
}
