<?php

/**
 * [!!] Note: If you want to make modifications to the default configurations,
 * it is highly recommended to copy this file into your applications config
 * folder and change them there.
 *
 * Doing this will allow you to upgrade your installation without losing custom
 * configurations.
 */

// Aliasing rules
use \Nerd\Environment as Env;
use \Nerd\Design\Architectural\MVC\View;
use \Nerd\Http;

/**
 * Error configuration
 *
 * @var    array
 */
return [

	/**
	 * The error reporting level for your application.
	 *
	 * Recommendations:
	 *   Development: E_ALL | E_STRICT
	 *   Production: E_ALL & ~E_DEPRECATED
	 *
	 * @see    http://php.net/manual/en/errorfunc.constants.php
	 * @var    integer
	 */
	'reporting' => function()
	{
		if(Env::$mode === Env::MODE_PRODUCTION)
		{
			return E_ALL | ~E_DEPRECATED;
		}

		return E_ALL;
	},

	/**
	 * Whether to enable display_errors.
	 *
	 * [!!] If your php.ini disables display_errors, your application may still
	 * display white screens of death on fatal errors.
	 *
	 * Env recommendations:
	 *   Development: true
	 *   Production: false
	 * 
	 * @var    boolean
	 */
	'display' => function()
	{
		if(Env::$mode === Env::MODE_PRODUCTION)
		{
			return false;
		}

		return true;
	},

	/**
	 * HTTP Error Handler
	 *
	 * This value should consist of a callback method that can be passed an
	 * HTTP error code, and perform whatever type of funcitonality you'd like
	 * when an Http\Exception is thrown. If you prefer to handle this type of
	 * error within a class, you can simply return a call to that class within
	 * your closure.
	 *
	 * @var    lambda
	 */
	'http_error_handler' => function($code)
	{
		return (new View('template'))->partial('content', 'error/http', [
			'code' => $code,
			'message' => Http::$statuses[$code]
		]);
	},

	/**
	 * Database Error Handler
	 *
	 * @var    lambda
	 */
	'db_error_handler' => function($code, $message)
	{
		return (new View('template'))->partial('content', 'error/db', [
			'code' => $code,
			'message' => $message
		]);
	},

];