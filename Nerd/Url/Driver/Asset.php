<?php

/**
* URL namespace. This namespace is reserved for classes relavent
* to dealing with (U)niform (R)esource (L)ocaters within Nerd.
*
* @package Nerd
* @subpackage URL
*/
namespace Nerd\Url\Driver;

/**
* HTTP Asset Url Driver
*
* @package Nerd
* @subpackage URL
*/
class Asset extends Site
{
    public function __construct($resource)
    {
        // NEED CONFIGURATION SOMEHOW
        $resource = ltrim($resource, '/');

        return parent::__construct($resource);
    }
}
