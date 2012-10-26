<?php

/**
 * Do not edit this file. If you need to do any framework bootstrapping you should
 * do it in the bootstrap.php file that lives within the application directory.
 */

namespace
{
	include 'functions.php';
}

namespace Nerd
{
	define('Nerd\LIBRARY_PATH', dirname(__DIR__));
	define('Nerd\VENDOR_PATH', dirname(LIBRARY_PATH).DIRECTORY_SEPARATOR.'vendor');
	define('Nerd\DOCROOT', dirname(LIBRARY_PATH).DIRECTORY_SEPARATOR.'public');

	if (!defined('DS')) {
		define('DS', DIRECTORY_SEPARATOR);
	}

	// Nerd Autoloader
	import('nerd', 'Nerd', 'autoloader.php') and (new Autoloader())->register();
	
	/**
	 * Test for CLI, load either application or Geek bootstrap.
	 */
	$application = PHP_SAPI === 'cli'
		? import('geek', 'resolve.php')
		: import('application', 'resolve.php');

	$vars = $application();

	/**
	 * Get and register the Composer autoloader as a secondary loader.
	 */
	$vendorLoader = import('..', 'vendor', 'autoload.php');
	$vendorLoader->register();

	/**
	 * Here you could do some magic for multiple applications by dynamically switching
	 * the application namespace for this request. To create another application you
	 * would simply create another subfolder in LIBRARY_PATH with its own Application
	 * class...
	 */
	define('Nerd\APPLICATION_NS', $vars['namespace']);
	define('Nerd\STORAGE_PATH', join(DS, [LIBRARY_PATH, $vars['storage'], 'storage']));

	/**
	 * Setup the current environment
	 */
	Environment::$active;

	error_reporting(Config::get('error.reporting'));
	ini_set('display_errors', (Config::get('error.display', true) ? 'On' : 'Off'));
	date_default_timezone_set(Config::get('application.timezone', 'UTC'));
	Str::$mbString and mb_internal_encoding(Config::get('application.encoding', 'UTF-8'));

	/**
	 * Create and execute the application we need!
	 */
	$application = ucfirst(APPLICATION_NS).'\\Application';
	$application = $application::instance();

	$application->triggerEvent('application.startup');

	if (property_exists($application, 'response')) {
		$application->response->send(true);
	} else {
		$application->send();
	}

	$application->triggerEvent('application.shutdown');
}