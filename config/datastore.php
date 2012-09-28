<?php

/**
 * [!!] Note: If you want to make modifications to the default configurations,
 * it is highly recommended to copy this file into your applications config
 * folder and change them there.
 *
 * Doing this will allow you to upgrade your installation without losing custom
 * configurations.
 */

/**
 * Datastore configurations
 *
 * @var    array
 */
return [

	/**
	 * Default Datastore driver
	 *
	 * The default driver to be utilized by your application in the event a
	 * specific driver isn't called.
	 *
	 * @var    string
	 */
	'driver' => 'file',

	/**
	 * Default cache time
	 *
	 * The default time (in minutes) to cache a value, in the event no time is
	 * provided.
	 *
	 * @var    integer
	 */
	'time' => 60,

	/**
	 * Datastore key
	 *
	 * This key will be prepended to item keys stored, to prevent collisions
	 * with other applications on the server.
	 *
	 * @var    string
	 */
	'key' => 'nerd_',

	/**
	 * Memcached servers
	 *
	 * The memcached servers used by your application.
	 *
	 * Memcached is a free and open source, high-performance, distributed memory
	 * object caching system, generic in nature, but intended for use in
	 * speeding up dynamic web applications by alleviating database load.
	 *
	 * For more information about Memcached, check out http://memcached.org
	 *
	 * @var    array
	 */
	'memcached' => [
		['host' => '127.0.0.1', 'port' => 11211, 'weight' => 100],
	],

];