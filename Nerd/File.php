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
 * Additionally, the File class inherits SPL's File Object class functionality
 * so it is able to operate directly on a file when not used in static context.
 *
 * @see http://www.php.net/manual/en/class.splfileobject.php
 *
 * @package    Nerd
 * @subpackage Core
 */
class File extends \SplFileObject
{
    /**
     * MIME configuration cache
     *
     * @var    array
     */
    public static $mimes;

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
    protected static $functionMap = [
        'exists'    => ['function' => 'file_exists'],
        'get'       => ['function' => 'file_get_contents'],
        'put'       => ['function' => 'file_put_contents', 'arguments' => [2 => LOCK_EX]],
        'type'      => ['function' => 'filetype'],
        'size'      => ['function' => 'filesize'],
        'modified'  => ['function' => 'filemtime'],
        'extension' => ['function' => 'pathinfo', 'arguments' => [1 => PATHINFO_EXTENSION]],
        'touch'     => ['function' => 'touch'],
    ];

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
     * @return mixed Returns the value of the intercepted method call
     */
    public static function __callStatic($name, array $arguments)
    {
        if (isset(static::$functionMap[$name]['arguments'])) {
            $arguments = $arguments + static::$functionMap[$name]['arguments'];
        }

        return forward_static_call_array(static::$functionMap[$name]['function'], $arguments);
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
     * @return   boolean          File was created successfully
     */
    public static function create($path, $content = null)
    {
        return ($content === null) ? touch($path) : (static::put($path, $content) !== false);
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
     * @return   integer|false    Number of bytes written, otherwise false
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
     * Get a file MIME type by extension or filename
     *
     * ## Usage
     *
     *     File::mime('my-file.txt');
     *
     * @param    string           The extension or filename to detect
     * @param    string           The default MIME to provide
     * @return   string           MIME associated to the extension, otherwise $default
     */
    public static function mime($extension, $default = null)
    {
        static::$mimes === null and static::$mimes = Config::get('mimes', []);

        if (strpos($extension, '.') !== false) {
            $extension = explode('.', $extension);
            $extension = array_pop($extension);
        }

        if (!isset(static::$mimes[$extension])) {
            return $default;
        }

        return (is_array(static::$mimes[$extension])) ? static::$mimes[$extension][0] : static::$mimes[$extension];
    }
}
