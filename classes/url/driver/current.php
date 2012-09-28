<?php

/**
* URL namespace. This namespace is reserved for classes relavent
* to dealing with (U)niform (R)esource (L)ocaters within Nerd.
*
* @package Nerd
* @subpackage URL
*/
namespace Nerd\Url\Driver;

// Aliasing rules
use \Nerd\Config;

/**
* Current URL Driver
*
* @package Nerd
* @subpackage URL
*/
class Current extends Http
{
	/**
	 * The directory beyond the domain for this application
	 *
	 * @var string
	 */
	protected $prefix;

	public function __construct($resource = null)
	{
		if (($url = Config::get('application.url')) === null)
		{
			throw new \InvalidArgumentException('You must set the application.url configuration variable in application.php');
		}

		$this->url($url);
		$this->prefix = trim($this->path, '/');
		$this->path   = null;
		$this->uri(str_replace($this->prefix, '', $_SERVER['REQUEST_URI']));
	}
}