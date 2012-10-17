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
* HTTP URL Driver
*
* The HTTP URL driver is the basis for all http URL requests. It is completely blank
* and is meant to be a starting point for subsequent drivers, but can be used
* indirectly to create a URI completely from scratch.
*
* @package Nerd
* @subpackage URL
*/
class Http extends \Nerd\Url\Driver
{
    /**
     * URI Scheme
     *
     * @var    string
     */
    protected $scheme = 'http';

    /**
     * Set whether to create a secure link
     *
     * @param     boolean     Create a secure URI?
     * @return Nerd\Uri\Driver\Http
     */
    public function secure($secure = true)
    {
        $this->scheme = $secure ? 'https' : 'http';

        return $this;
    }
}
