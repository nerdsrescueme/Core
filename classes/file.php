<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * File class
 *
 * The File class provides a conveninent object oriented wrapper around file
 * functionality. Some of these methdos are direct mapping to native PHP
 * functions, while others exist to help solve common issues that arise that
 * file functions do not directly solve.
 *
 * @package    Nerd
 * @subpackage Core
 */
class File
{
	/**
	 * MIME configuration cache
	 *
	 * @var    array
	 */
	protected static $mimes;

	/**
	 * Function map
	 *
	 * The functions in this map are one to one maps of native PHP
	 * functionality, with optional arguments that pertain to the mapped
	 * function. You can call all of these functions as you would the normal
	 * function (e.g. File::exists() == file_exists(), with the same arguments).
	 *
	 * @var    array
	 */
	protected static $functionMap = array(
		'exists'    => array('function' => 'file_exists'),
		'get'       => array('function' => 'file_get_contents'),
		'put'       => array('function' => 'file_put_contents', 'arguments' => array(2 => LOCK_EX)),
		'type'      => array('function' => 'filetype'),
		'size'      => array('function' => 'filesize'),
		'modified'  => array('function' => 'filemtime'),
		'extension' => array('function' => 'pathinfo', 'arguments' => array(1 => PATHINFO_EXTENSION)),
	);

	/**
	 * The magic call static method is triggered when invoking inaccessible
	 * methods in a static context.
	 *
	 * ## Usage
	 *
	 * Any of the values that exist within the $functionMap can be utilized
	 * through this method. Please check each of the functions that exist in
	 * there, and check http://php.net for usage on each of the functions.
	 *
	 * @param    string           The method name being called
	 * @param    array            The arguments being passed to the method call
	 * @return   mixed            Returns the value of the intercepted method call
	 */
	public static function __callStatic($name, array $arguments)
	{
		return call_user_func_array(static::$functionMap[$name]['function'], $arguments);
	}

	/**
	 * Create a new file
	 *
	 * ## Usage
	 *
	 *     File::create('my-file.txt');
	 *     File::create('my-file.txt', 'Some starting content');
	 *
	 * @param    string           The data to assign to the newly created file
	 * @return   boolean          Returns true if the new file was created successfully, otherwise false
	 */
	public static function create($path, $content = null)
	{
		return ($content === null) ? touch($path) : static::put($path, $content);
	}

	/**
	 * Append contents to a file
	 *
	 * ## Usage
	 *
	 *     File::append('my-file.txt', 'Appending this content');
	 *
	 * @param    string           The path to the file
	 * @param    string           The contents to write to the file
	 * @param    integer          Custom flags
	 * @return   integer          Returns the number of bytes that were written to the file, otherwise false
	 */
	public static function append($path, $content, $flags = null)
	{
		$flags === null and $flags = LOCK_EX | FILE_APPEND;
		return static::put($path, $content, $flags);
	}

	/**
	 * Delete a file
	 *
	 * ## Usage
	 *
	 *     File::delete('my-file.txt');
	 *
	 * @param    string           The path to the file
	 * @return   void             No value is returned
	 */
	public static function delete($path)
	{
		static::exists($path) and @unlink($path);
	}

	/**
	 * Get a file MIME type by extension
	 *
	 * ## Usage
	 *
	 *     File::mime('my-file.txt');
	 *
	 * @param    string           The extension to detect
	 * @param    string           The default MIME to provide, if none is detected. Defaults to null
	 * @return   string           Returns the MIME associated to the extension, otherwise $default
	 */
	public static function mime($extension, $default = null)
	{
		static::$mimes === null and Config::get('mimes', []);

		if(!isset(static::$mimes[$extension]))
		{
			return $default;
		}

		return (Arr::is(static::$mimes[$extension])) ? static::$mimes[$extension][0] : static::$mimes[$extension];
	}

	/**
	 * Determine if a file is of a given type.
	 *
	 * The Fileinfo PHP extension will be used to determine the MIME type of the
	 * file.
	 *
	 *     File::is('my-file.txt', 'txt');
	 *
	 * @param    string           The path to the file
	 * @param    string|array     An extension, or array of extensions to match
	 * @return   boolean          Returns true if the file type matched the extension, otherwise false
	 */
	public static function is($path, $extensions)
	{
		static::$mimes === null and Config::get('mimes', []);

		foreach((array) $extensions as $extension)
		{
			$mime = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);

			if(isset($mimes[$extension]) and in_array(static::$mimes, (array) $mimes[$extension]))
			{
				return true;
			}
		}

		return false;
	}
}