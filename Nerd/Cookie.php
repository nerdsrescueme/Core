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
 * Cookie class
 *
 * The Cookie class provides helper functionality for dealing with cookies, in
 * a more convenient manner. Additionally, this class provides an extra layer
 * of ambiguity to the cookie to help prevent unanticipated modification.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Cookie
{
    // Traits
    use Design\Singleton;

    /**
     * Determine if a cookie exists.
     *
     * ## Usage
     *
     *     $boolean = $cookie->has('name');
     *
     * @param    string           The name of the cookie
     * @return boolean Returns true if the cookie exists, otherwise false
     */
    public function has($name)
    {
        return !is_null($this->get($name));
    }

    /**
     * Get the value of a cookie.
     *
     * ## Usage
     *
     *     $cookie->get('nameOfCookie', 'defaultValue');
     *
     * @param    string           The name of the cookie
     * @param    mixed            A default to provide in the event the cookie doesn't exist, defaults to null
     * @return mixed Returns the value of the cookie, or $default if it doesn't exist
     */
    public function get($name, $default = null)
    {
        $value = Arr::get($_COOKIE, $name);

        if (!is_null($value)) {
            if (isset($value[40]) and $value[40] === '-') {
                list($hash, $value) = explode('-', $value, 2);

                if (Crypt::hash($name.$value) === $hash) {
                    return $value;
                }
            }
        }

        return is_callable($default) ? $default() : $default;
    }

    /**
     * Set a "permanent" cookie. The cookie will last for one year.
     *
     * ## Usage
     *
     *     echo ($cookie->setForever('name', 'value')) ? 'The cookie was set!' : 'Failed!';
     *
     * @param    string           The name of the cookie
     * @param    string           The value to assign to the cookie
     * @param    string           The path of the cookie to set, defaults to '/'
     * @param    boolean          Whether the domain should only be set over a secure connection, defaults to false
     * @param    boolean          Whether the domain should only be set over http, defaults to false
     * @return boolean Returns true if the cookie was set, otherwise false
     */
    public function setForever($name, $value, $path = '/', $domain = null, $secure = false, $http_only = false)
    {
        return $this->put($name, $value, 525600, $path, $domain, $secure, $http_only);
    }

    /**
     * Set the value of a cookie.
     *
     * If a negative number of minutes is specified, the cookie will be deleted.
     *
     * This method's signature is very similar to the PHP setcookie method.
     * However, you simply need to pass the number of minutes for which you
     * wish the cookie to be valid. No funky time calculation is required.
     *
     * ## Usage
     *
     *     echo ($cookie->set('name', 'value', 3600)) ? 'The cookie was set!' : 'Failed!';
     *
     * @param    string           The name of the cookie
     * @param    string           The value to assign to the cookie
     * @param    string           The path of the cookie to set, defaults to '/'
     * @param    boolean          Whether the domain should only be set over a secure connection, defaults to false
     * @param    boolean          Whether the domain should only be set over http, defaults to false
     * @return boolean Returns true if the cookie was set, otherwise false
     */
    public function set($name, $value, $minutes = 0, $path = '/', $domain = null, $secure = false, $http_only = false)
    {
        if (headers_sent()) {
            return false;
        }

        $time  = ($minutes !== 0) ? time() + ($minutes * 60) : 0;
        $value = Crypt::hash($name.$value).'~'.$value;

        if ($minutes < 0) {
            unset($_COOKIE[$name]);
        } else {
            $_COOKIE[$name] = $value;
        }

        return setcookie($name, $value, $time, $path, $domain, $secure, $http_only);
    }

    /**
     * Delete a cookie.
     *
     * ## Usage
     *
     *     echo ($cookie->delete('name')) ? 'The cookie was deleted!' : 'Failed!';
     *
     * @param    string           The name of the cookie
     * @return boolean Returns true if the cookie was deleted, otherwise false
     */
    public function delete($name)
    {
        return $this->set($name, null, -2000);
    }
}
