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
class Input implements Design\Initializable
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

    /**
     * Static Constructor
     *
     * Checks if the current request is an AJAX request and what HTTP method was used
     * when submitting the request.
     *
     * @return void
     */
    public static function __initialize()
    {
        $req = static::server('http_x_requested_with', false);

        static::$ajax      = $req and strtolower($req) == 'xmlhttprequest';
        static::$method    = strtolower(static::server('request_method', 'get'));
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
        return $key === null ? $_COOKIE : \Nerd\Arr::get($_COOKIE, $key, $default);
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
        return $key === null ? $_ENV : \Nerd\Arr::get($_ENV, $key, $default);
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
        return $key === null ? $_FILES : \Nerd\Arr::get($_FILES, $key, $default);
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
        return $key === null ? $_GET : \Nerd\Arr::get($_GET, $key, $default);
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
        return $key === null ? $_POST : \Nerd\Arr::get($_POST, $key, $default);
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

        return $key === null ? static::$_PUT : \Nerd\Arr::get(static::$_PUT, $key, $default);
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
        return $key === null ? $_SERVER : \Nerd\Arr::get($_SERVER, strtoupper($key), $default);
    }
}
