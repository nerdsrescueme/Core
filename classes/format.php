<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Format class
 *
 * The format class provides the ability to interface with raw data types as
 * arrays, through the drivers.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Format extends Design\Creational\SingletonFactory
{
}