<?php

/**
 * Core Nerd library namespace. This namespace contains all of the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
* Input Class
*
* The input class handles all user and browser submitted information relevant to
* this request. It has convenience methods for accessing individual pieces of
* information within the aforementioned relevant info.
*
* @todo  Add put, delete methods.
*
* @package Nerd
* @subpackage Core
*/
class Input
{
    /**
     * Is the current request an AJAX request?
     *
     * @var boolean
     */
    public static $ajax = false;

    /**
     * HTTP Request Method
     *
     * @var string
     */
    public static $method = 'get';

    /**
     * Emulated HTTP PUT superglobal
     *
     * @var array
     */
    private static $_PUT;


    public static function ajax()
    {
        if (static::$ajax === null) {
            $req = static::server('http_x_requested_with', false);
            static::$ajax = $req and strtolower($req) == 'xmlhttprequest';
        }

        return static::$ajax;
    }

    public static function method()
    {
        if (static::$method === null) {
            static::$method = strtolower(static::server('request_method'));
        }

        return static::$method;
    }

    /**
     * Retrieve data from the $_COOKIE superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function cookie($key = null, $default = null)
    {
        return $key === null ? $_COOKIE : Arr::get($_COOKIE, $key, $default);
    }

    /**
     * Retrieve data from the $_ENV superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function env($key = null, $default = null)
    {
        return $key === null ? $_ENV : Arr::get($_ENV, $key, $default);
    }

    /**
     * Retrieve data from the $_FILES superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function file($key = null, $default = null)
    {
        return $key === null ? $_FILES : Arr::get($_FILES, $key, $default);
    }

    /**
     * Retrieve data from a superglobal array and filter it on the way in
     *
     * @param    string          Which superglobal to use
     * @param    string          Dot notated path to data
     * @param    integer         Filter to run
     * @param    integer         Flags for a given filter
     * @return   mixed           Filtered data
     */
    public static function filter($type, $key, $filter, $flags = null)
    {
        if (($data = static::{$type}($key)) === null) {
            return null;
        }

        if (is_array($data)) {
            throw new \InvalidArgumentException('The data returned from the superglobal cannot be an array');
        }

        return filter_var($data, $filter, $flags ?: null);
    }

    /**
     * Retrieve data from the $_GET superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function get($key = null, $default = null)
    {
        return $key === null ? $_GET : Arr::get($_GET, $key, $default);
    }

    /**
     * Return to "real" IP address of the current user, this method will discover
     * any IP behind a proxy if at all possible.
     *
     * @param     string     Default return value
     * @return string IP
     */
    public static function ip($default = '0.0.0.0')
    {
        if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
            return $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        }

        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }

        // detection failed, return the default
        return $default;
    }

    /**
     * Retrieve data from the $_POST superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function post($key = null, $default = null)
    {
        return $key === null ? $_POST : Arr::get($_POST, $key, $default);
    }

    /**
     * Reads the current csrf token and determines if the form is safe.
     *
     * @returns   void
     */
    public static function protect($valid)
    {
        $enabled = Config::get('application.csrf.enabled', false);
        $field   = Config::get('application.csrf.field', '@@state');

        if ($enabled and static::$method === 'post') {
            if (($token = static::post($field, false)) !== false) {
                return $token === $valid;
            }
        }

        return true;
    }

    /**
     * Retrieve data from the emulated $_PUT superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function put($key = null, $default = null)
    {
        if (static::$_PUT === null) {
            parse_str(file_get_contents('php://input'), static::$_PUT);
        }

        return $key === null ? static::$_PUT : Arr::get(static::$_PUT, $key, $default);
    }

    /**
     * Recursively sanitize a given array
     *
     * 1. Standardize newlines to "\n"
     *
     * @param    mixed          Input value or array
     * @return   mixed          Sanitized input value or array
     */
    public static function sanitize($value)
    {
        if (is_array($value) OR is_object($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = static::sanitize($val);
            }
        } elseif (is_string($value)) {
            if (strpos($value, "\r") !== FALSE) {
                $value = str_replace(array("\r\n", "\r"), "\n", $value);
            }
        }

        return $value;
    }

    /**
     * Retrieve data from the $_SERVER superglobal array
     *
     * @param     string     Dot notated path to data
     * @param     mixed      Default return value
     * @returns   mixed
     */
    public static function server($key = null, $default = null)
    {
        return $key === null ? $_SERVER : Arr::get($_SERVER, strtoupper($key), $default);
    }
}
