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

/**
* Source Parameter Class
*
* The purpose of this class is to create a usable representation of a
* method parameter using PHP's Reflection API.
*
* @package Nerd
* @subpackage Source
*/
class Parameter extends \ReflectionParameter
{
    use \Nerd\Source\Traits\ParentClass;

    /**
     * Get this parameters calling (@see Nerd\Source\Klass) object
     *
     * @return Nerd\Source\Klass
     */
    public function getClass()
    {
        return new Klass(parent::getClass()->getName());
    }

    /**
     * Get this parameters declaring (@see Nerd\Source\Funktion) object
     *
     * @return Nerd\Source\Funktion
     */
    public function getDeclaringFunction()
    {
        $function = parent::getDeclaringFunction();

        if ($function instanceof \ReflectionMethod) {
            return new Method($this->getDeclaringClass()->getName(), $function->getName());
        }

        return new Funktion($function->getName());
    }
}
