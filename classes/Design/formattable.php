<?php

/**
 * Design namespace. This namespace is meant to provide abstract concepts and in
 * most cases, simply interfaces that in someway structures the general design
 * used in core components. Additionally, the Design namespace provides sub
 * namespaces that relate specifically to common design patterns that can be
 * attached to classes without duplication of functionality.
 *
 * @package Nerd
 * @subpackage Design
 */
namespace Nerd\Design;

/**
 * Formattable trait
 *
 * @package    Nerd
 * @subpackage Core
 */
trait Formattable
{
    /**
     * Json serialization
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return (array) $this->__sleep();
    }

    public function serialize()
    {
        return (array) $this->__sleep();
    }
}
