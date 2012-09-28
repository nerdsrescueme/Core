<?php

/**
 * Core Nerd library namespace. This namespace contains all of the fundamental
 * components of the Nerd framework, plus additional utilities that are
 * provided by default. Some of these default components have sub namespaces
 * if they provide children objects.
 *
 * @package    Nerd
 * @subpackage Library
 */
namespace Nerd;

/**
 * Database Class
 *
 * It is very important that you understand the PHP PDO classes in order to
 * understand how the Nerd Database layer operates. Essentially, the Nerd DB layer is
 * an extension of the PDO classes providing singleton access to a default database
 * connection.
 *
 * @package    Nerd
 * @subpackage Library
 */
class DB extends \PDO {

	/**
	 * Database connections
	 *
	 * @var    array
	 */
	private static $connections = [];

	/**
	 * Last used database connection
	 *
	 * @var    array
	 */
	private static $activeConnection;

	/**
	 * Create or retrieve a database connection
	 *
	 * Provides a means to connect and configure database connections through PDO. It
	 * sets the following connection attributes.
	 *
	 * 1. Throw exceptions instead of errors
	 * 2. Use Nerd's extended Statement class
	 * 3. Default fetch mode is object
	 *
	 * ## Usage
	 * 
	 *     $connection  = DB::connection()
	 *     $conn-backup = DB::connection('secondary');
	 *
	 * @param     string     Connection identifier
	 * @return    \Nerd\DB
	 */
	public static function connection($id = 'default')
	{
		if(!isset(static::$connections[$id]))
		{
			// Get and build DSN from Config

			list($dsn, $user, $password, $options) = array(
				'mysql:dbname=new_nerd;host=127.0.0.1;charset=UTF-8', 'root', '', []
			);

			static::$connections[$id] = new static($dsn, $user, $password, $options);

			static::$connections[$id]->setAttribute(static::ATTR_ERRMODE, static::ERRMODE_EXCEPTION);
			static::$connections[$id]->setAttribute(static::ATTR_STATEMENT_CLASS, 
				array('\\Nerd\\DB\\Statement', array(static::$connections[$id])));
			static::$connections[$id]->setAttribute(static::ATTR_DEFAULT_FETCH_MODE, static::FETCH_OBJ);

			static::$connections[$id]->id = $id;
			static::$connections[$id]->database = 'new_nerd'; // Make dynamic
		}

		static::$activeConnection = &static::$connections[$id];
		return static::$connections[$id];
	}

	/**
	 * Retrive the active connection
	 *
	 * The active connection is the connection that was last created via the
	 * connection method.
	 *
	 * @return    Nerd\DB          Most recent database instance
	 */
	public static function active()
	{
		if (static::$activeConnection === null)
		{
			static::$activeConnection = static::connection();
		}

		return static::$activeConnection;
	}

	/**
	 * Magic static caller
	 *
	 * Allows static usage of methods on the default database instance.
	 */
	public static function __callStatic($method, $params)
	{
		$instance = static::connection();

		return call_user_func_array(array($instance, $method), $params); 
	}

	/**
	 * Instance identifier
	 *
	 * @var    string
	 */
	protected $id;
}