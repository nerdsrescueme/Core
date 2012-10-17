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
* Source Property Class
*
* The Nerd Source Property class is meant to provide a usable interface
* for working with the PHP Reflection API within Nerd.
*
* @package Nerd
* @subpackage Source
*/
class Property extends \ReflectionProperty
{
    use \Nerd\Source\Traits\Docblock
      , \Nerd\Source\Traits\ParentClass;
}
