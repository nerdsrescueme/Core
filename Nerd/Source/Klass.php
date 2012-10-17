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
* Source Klass Class
*
* The Klass Class is named Klass because Class is a reserved word in PHP.
* The purpose of this class is to create a usable representation of a
* class using PHP's Reflection API.
*
* @package Nerd
* @subpackage Source
*/
class Klass extends \ReflectionClass
{
    use \Nerd\Source\Traits\Docblock
      , \Nerd\Source\Traits\ParentClass;

    /**
     * Enumerable array of class's methods
     *
     * @var    Nerd\Design\Collection
     */
    protected $methods;

    /**
     * Enumerable array of class's properties
     *
     * @var    Nerd\Design\Collection
     */
    protected $properties;

    /**
     * Get the entire classes source code as a string
     *
     * @todo      Use (@see Nerd\Source\File) to render this classes content.
     * @param     boolean     Include the docblock with the source code?
     * @return string Class source code
     */
    public function getContents($include_docblock = true)
    {
        $lines = file($this->getFileName());

        return implode('', array_splice(
            $lines,
            $this->getStartLine($include_docblock) - 1,
            $this->getEndLine() - $this->getStartLine($include_docblock) + 1,
            true
        ));
    }

    /**
     * Get the absolute file name for this class
     *
     * @return string Absolute file path for this class
     */
    public function getDeclaringFile()
    {
        return new \Nerd\Source\File($this->getFileName());
    }

    /**
     * Get all interfaces used by this class
     *
     * @return Nerd\Design\Enumerable Enumerable array of interfaces used by this class
     */
    public function getInterfaces()
    {
        $interfaces = parent::getInterfaces();

        foreach ($interfaces as $key => $interface) {
            $interfaces[$key] = new Klass($interface->getName());
        }

        return new Collection($interfaces);
    }

    /**
     * Get a method with a given name
     *
     * @throws Exception          Method does not exist on this class
     * @return Nerd\Source\Method
     */
    public function getMethod($name)
    {
        return new Method($this->getName(), parent::getMethod($name)->getName());
    }

    /**
     * Get all methods defined in this class
     *
     * @todo    Document filter paramater, not sure what it does yet.
     * @param   integer
     * @return Nerd\Design\Enumerable And enumerable array containing class methods
     */
    public function getMethods($filter = '-1')
    {
        if ($this->methods === null) {
            $methods = parent::getMethods();

            foreach ($methods as $key => $method) {
                $methods[$key] = new Method($this->getName(), $method->getName());
            }

            $this->methods = new Collection($methods);
        }

        return $this->methods;
    }

    /**
     * Get a class property with a given name
     *
     * @throws Exception            Property does not exist on this class
     * @return Nerd\Source\Property
     */
    public function getProperty($name)
    {
        return new Property($this->getName(), parent::getProperty($name)->getName());
    }

    /**
     * Get all properties defined in this class
     *
     * @todo    Document filter paramater, not sure what it does yet.
     * @param   integer
     * @return Nerd\Design\Enumerable And enumerable array containing class properties
     */
    public function getProperties($filter = -1)
    {
        if ($this->properties === null) {
            $properties = parent::getProperties($filter);

            foreach ($properties as $key => $property) {
                $properties[$key] = new Property($this->getName(), $property->getName());
            }

            $this->properties = new Collection($properties);
        }

        return $this->properties;
    }

    /**
     * Get the starting line of this class
     *
     * @param    boolean     Start from the docblock?
     * @return integer Starting line number for this class
     */
    public function getStartLine($include_docblock = false)
    {
        return ($include_docblock) ? $this->getDocblock()->getStartLine() : parent::getStartLine();
    }
}
