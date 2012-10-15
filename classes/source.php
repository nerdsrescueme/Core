<?php

/**
* Core Nerd library namespace. This namespace contains all of the fundamental
* components of Nerd, plus additional utilities that are provided by default.
* Some of these default components have sub namespaces if they provide child
* objects.
*
* @pacakge Nerd
* @subpackage Source
*/
namespace Nerd;

/**
* Nerd Source
*
* Nerd SOURCE is a set of tools designed to allow developers to work directly
* with code to allow framework level access.
*
* The Source class is designed to provide functionality to all Nerd _Source_
* components. Think of it as a utility library for Nerd _Source_, providing base
* level access to components as well as common class functionality.
*
* @pacakge Nerd
* @subpackage Source
*/
class Source
{
    /**
     * Get a SOURCE Class object
     *
     * @param     string      Fully namespaced class to load
     * @return Nerd\Source\Klass
     */
    public static function getClass($class)
    {
        return new Source\Klass($class);
    }

    /**
     * Get a SOURCE Method object
     *
     * @param     string     Fully namespaced class to load
     * @param     string     Class method to retrieve
     * @return Nerd\Source\Method
     */
    public static function getMethod($class, $method)
    {
        return new Source\Method($class, $method);
    }
}
