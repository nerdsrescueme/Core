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
use Nerd\Environment as Env;

/**
 * Environment configuration
 *
 * This file contains all of the environments that you reference throughout
 * your application. Each environment can be determined by any value from the
 * $_SERVER array, but generally is one that is unique such as SERVER_ADDR.
 *
 * It is recommended to name your environments after the name of its server,
 * rather than its environment type.
 *
 * @var    array
 */
return [

	'localhost' => [
		'identifier' => ['127.0.0.1', '::1', 'localhost'],
		'field'      => 'SERVER_ADDR',
		'mode'       => Env::MODE_DEVELOPMENT,
	],

];