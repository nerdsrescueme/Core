<?php

/**
 * The Http namespace aims to provide convenient and powerful tools for working
 * with data in an http context (both sending an recieving).
 *
 * @package    Nerd
 * @subpackage Http
 */
namespace Nerd\Http;

/**
 * Response class
 *
 * The Http Response class is a class meant to aid in the sending of your Http
 * response. It provides some basic security defaults, and allows you to
 * conveniently set status codes, headers, or other information.
 *
 * @package    Nerd
 * @subpackage Http
 */
class Response extends \Nerd\Http
{
	/**
	 * Method-chainable constructor method, providing ease-of-use and cleaner
	 * options when working with class construction.
	 *
	 * ## Usage
	 *
	 *     $response = Response::instance();
	 *
	 * @see      static::__construct()
	 */
	public static function instance($body = null, $status = null)
	{
		return new static($body, $status);
	}
	
	/**
	 * The HTTP standard protocol to utilize
	 *
	 * @var    string
	 */
	public $protocol = 'HTTP/1.1';

	/**
	 * The HTTP status code of this response
	 *
	 * @var    integer
	 */
	public $status = 200;

	/**
	 * Response headers
	 *
	 * @var    array
	 */
	public $headers = [];

	/**
	 * Body of the request
	 *
	 * @var    mixed
	 */
	public $body;

	/**
	 * Creates a new instance to the Http Response class
	 *
	 * ## Usage
	 *
	 *     $response = new Response();
	 *
	 * @param    string           The body of the request
	 * @param    integer          The response status code
	 * @return   Response         Returns a new instance to the Response class
	 */
	public function __construct($body = null, $status = null)
	{
		$body !== null and $this->body($body);
		$status !== null and $this->setStatus($status);
	}
	
	/**
	 * Redirect (document)
	 */
	public function redirect($to = null, $status = 302)
	{
		if(headers_sent())
		{
			throw new \RuntimeException("Cannot redirect to [$to] after headers have already been sent.");
		}

		$this->headers = [];
		$this->setStatus($status)->setHeader('Location', $to)->send(true);
		exit;
	}

	/**
	 * Add or append an existing header for this response
	 *
	 * ## Usage
	 *
	 *     $response->setHeader('Location', 'http://google.com');
	 *
	 * @param    string           The literal name of the header
	 * @param    string           The value of the header key
	 * @return   Response         Returns the current instance of the Response object
	 */
	public function setHeader($name, $value)
	{
		$this->headers[$name] = $value;
		return $this;
	}

	/**
	 * Sets the status code for this response
	 *
	 * ## Usage
	 *
	 *     $response->setStatus(404);
	 *
	 * @param    integer          The response status code
	 * @return   Response         Returns the current instance of the Response object
	 */
	public function setStatus($status = 200)
	{
		$this->status = $status;
		return $this;
	}

	/**
	 * Sets the body for this response
	 *
	 * ## Usage
	 *
	 *     $response->setBody('Hello World');
	 *
	 * @param    string           The response body to be set
	 * @return   Response         Returns the current instance of the Response object
	 */
	public function setBody($value = null)
	{
		$this->body = $value;
		return $this;
	}

	/**
	 * Sends the headers to the client, if they haven't already been sent.
	 *
	 * ## Usage
	 *
	 *     $response->sendHeaders();
	 *
	 * @return   Response         Returns the current instance of the Response object
	 */
	public function sendHeaders()
	{
		if(!headers_sent())
		{
			\header($this->protocol.' '.$this->status.' '.static::$statuses[$this->status]);

			foreach($this->headers as $name => $value)
			{
				\header($name.': '.$value, true);
			}
		}

		return $this;
	}

	/**
	 * Send the response to the browser
	 *
	 * ## Usage
	 *
	 *     $response->send();
	 *     $response->send(true); // Sends the headers with the response
	 *
	 * @return   Response         Returns the current instance of the Response object
	 */
	public function send($send_headers = false)
	{
		if(!isset($this->headers['Content-Type']))
		{
			$this->setHeader('Content-Type', 'text/html; charset=utf-8');
		}

		$this->sendHeaders();

		if(is_object($this->body) and $this->body instanceof \Nerd\Design\Renderable)
		{
			$this->body = $this->body->render();
		}

		if(!empty($this->body))
		{
			echo $this->body;
		}
	}
}