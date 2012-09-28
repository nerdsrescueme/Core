<?php

/**
* URL namespace. This namespace is reserved for classes relavent
* to dealing with (U)niform (R)esource (L)ocaters within Nerd.
*
* @package Nerd
* @subpackage URL
*/
namespace Nerd\Url;

/**
* URL Abstract Driver
*
* The URL abstract driver acts as the base of all URL subclasses in
* Nerd. All custom URL classes should extend this driver file.
*
* @package Nerd
* @subpackage URL
*/
abstract class Driver
{
	// Traits
	use \Nerd\Design\Renderable;

	/**
	 * URI scheme (http, ftp, https, etc...)
	 *
	 * @var    string
	 */
	protected $scheme;

	/**
	 * Hostname (nerdphp.com, github.com)
	 *
	 * @var    string
	 */
	protected $host;

	/**
	 * Requested port
	 *
	 * @var    integer
	 */
	protected $port;

	/**
	 * HTTP username
	 *
	 * @var    string
	 */
	protected $user;

	/**
	 * HTTP password
	 *
	 * @var    string
	 */
	protected $pass;

	/**
	 * Path to requested resource
	 *
	 * @var    string
	 */
	protected $path;

	/**
	 * Format of requested resource (.html, .xml, .json, etc...)
	 *
	 * @var    string
	 */
	protected $format;

	/**
	 * Query string parameters
	 *
	 * @var    array
	 */
	protected $parameters;

	/**
	 * URI fragment (#section, #page2, etc...)
	 *
	 * @var    string
	 */
	protected $fragment;

	public function __construct($resource = null)
	{
		if ($resource === null)
		{
			return;
		}
		elseif (strpos($resource, '://') !== false)
		{
			// We have a full URL
			$this->url($resource);
		}
		else
		{
			// We have a URI
			$this->uri($resource);
		}
	}

	public function url($resource = null)
	{
		extract(parse_url($resource));

		isset($scheme) and $this->scheme = $scheme;
		isset($host)   and $this->host = $host;
		isset($user)   and $this->user = $user;
		isset($pass)   and $this->pass = $pass;
		isset($query)  and parse_str($query, $this->parameters);
		isset($fragment) and $this->fragment = $fragment;

		if (isset($path))
		{
			extract(pathinfo($path));
			isset($extension) and $this->format = $extension;
			$this->path = str_replace(".{$this->format}", '', $path);
		}
	}

	public function uri($resource = null)
	{
		if ($resource === null)
		{
			$uri  = $this->path !== null ? '/'.ltrim($this->path, '/') : '';
			$uri .= $this->format !== null ? '.'.$this->format : '';
			$uri .= count($this->parameters) > 0 ? '?'.http_build_query($this->parameters) : '';
			$uri .= $this->fragment !== null ? '#'.$this->fragment : '';

			return $uri;
		}

		// Assume we have a URI
		if (strpos($resource, '#') !== false)
		{
			list($resource, $this->fragment) = explode('#', $resource);
		}

		if (strpos($resource, '?') !== false)
		{
			list($resource, $this->parameters) = explode('?', $resource);
			parse_str($this->parameters, $this->parameters);
		}

		extract(pathinfo($resource));
		isset($extension) and $this->format = $extension;
		$this->path = str_replace(".{$this->format}", '', $resource);
	}

	/**
	 * Get a specific segment from the URI path
	 *
	 * @param     integer     Array offset requested
	 * @returns   string      Path at $offset
	 */
	public function segment($offset)
	{
		$segments = explode('/', trim($this->path, '/'));

		return $segments[$offset];
	}

	/**
	 * Get all segments from the URI path
	 *
	 * @returns    array     Array of URI segments
	 */
	public function segments()
	{
		return explode('/', trim($this->path, '/'));
	}

	/**
	 * Get the full URL for a dynamic URI
	 *
	 * Replace all named segments in the URI path with data provided
	 * to this function
	 *
	 *     $uri = Uri::site('(:year)/(:month)/blog-post
	 *     $uri->getDynamic(['year' => date('Y'), 'month' => date('M')]);
	 *     
	 *     // outputs http://mysite.com/2011/01/blog-post
	 *
	 * @param     array     Array with key/value replacement pairs
	 * @returns   string    URL with replaced segments
	 */
	public function inject($params = [])
	{

	}

	/**
	 * Check if URI is valid
	 *
	 * @returns     boolean    True if URI is valid
	 */
	public function validate()
	{
		return filter_var($this->render(), FILTER_VALIDATE_URL) !== false;
	}

	/**
	 * Get URL as string
	 *
	 * @returns    string    URL as string
	 */
	public function render()
	{
		$uri  = $this->scheme.'://';
		$uri .= $this->pass !== null ? $this->user.'@'.$this->pass : '' ;
		$uri .= $this->host;
		$uri .= $this->port !== null ? ':'.$this->port : '';
		$uri .= $this->path !== null ? '/'.ltrim($this->path, '/') : '';
		$uri .= $this->format !== null ? '.'.$this->format : '';
		$uri .= count($this->parameters) > 0 ? '?'.http_build_query($this->parameters) : '';
		$uri .= $this->fragment !== null ? '#'.$this->fragment : '';

		return $uri;
	}
}