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
* HTTP Site Url Driver
*
* @package Nerd
* @subpackage URL
*/
class Site extends Http
{
	/**
	 * The directory beyond the domain for this application
	 *
	 * @var string
	 */
	protected $prefix;

	public function __construct($resource)
	{
		if (($url = Config::get('application.url')) === null)
		{
			throw new \InvalidArgumentException('You must set the application.url configuration variable in application.php');
		}

		parent::url($url);
		$this->prefix = trim($this->path, '/');
		$this->path   = null;
		$this->uri($resource);
	}

	/**
	 * Get URL as string
	 *
	 * @returns    string    URL as string
	 */
	public function render()
	{
		$uri = parent::render();
		$uri = str_replace($this->host, $this->host.'/'.$this->prefix, $uri);

		return $uri;
	}
}