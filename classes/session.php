<?php

/**
* Core Nerd library namespace. This namespace contains all of the fundamental
* components of the Nerd framework, plus additional utilities that are
* provided by default. Some of these default components have sub namespaces
* if they provide children objects.
*
* @package Nerd
* @subpackage Core
*/
namespace Nerd;

/**
* Session Class
*
* The session class provides functionality for working directly with PHP
* $_SESSION data. The Nerd implementation of sessions centers around the
* default way of session handling in PHP. All we basically do is automatically
* set the session handler functions, provide convenient access to the $_SESSION
* superglobal array, and add a Flash class into the mix
*
* @package Nerd
* @subpackage Core
*/
class Session implements Design\Initializable, Design\Serializable
{
    // Traits
    use Design\Creational\Singleton
      , Design\Eventable
      , Design\Formattable;

    /**
     * Static constructor
     *
     * Sets the session save handler and data based on configuration
     * data, reads data from the session and sets up our flash subclass.
     *
     * @throws RuntimeException when session handler can't be setup
     * @returns   Nerd\Session
     */
    public static function __initialize()
    {
        ini_set('session.gc_maxlifetime', Config::get('session.lifetime'));
        ini_set('session.cookie_lifetime', Config::get('session.cookieLifetime'));
        ini_set('session.name', Config::get('session.name'));
        ini_set('session.gc_probability', Config::get('session.gc.probability'));
        ini_set('session.gc_divisor', Config::get('session.gc.divisor'));

        $handler = Config::get('session.handler');

        $session = static::instance();
        $handler = new $handler();
        $set     = session_set_save_handler($handler, true);

        if (!$set) {
            throw new Session\Exception('Error setting session save handler.');
        }

        if (Config::get('session.useFlash', false)) {
            $session->flash = \Nerd\Session\Flash::instance();
        }

        $session->triggerEvent('session.start', array($session));
        session_start();
        $session->triggerEvent('session.setup', array($session));
    }

    /**
     * Session flash object
     *
     * @var   Nerd\Session\Flash
     */
    public $flash;

    /**
     * Clear the current session of all data
     *
     * @returns    void
     */
    public function clear()
    {
        $_SESSION = [];
    }

    /**
     * Delete an item from the session data
     *
     * @param     string     Session data key
     * @returns   boolean    True if successful
     */
    public function delete($key)
    {
        return Arr::delete($_SESSION, $key);
    }

    /**
     * Completely destroy the current session
     *
     * This will remove all session data, unset the session cookie then
     * ultimately destroy the session as per the loaded drivers destroy()
     * method calls for.
     *
     * @returns    void
     */
    public function destroy()
    {
        $this->clear();

        if (Config::get('session.useCookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }

        $this->triggerEvent('session.destroy', [$this]);
        session_destroy();
    }

    /**
     * Get an item from the session data using javascript dot notation
     *
     * @param     string     Session data key
     * @param     mixed      Default value to return if not found in session
     * @returns   mixed      Session data value
     */
    public function get($key, $default = null)
    {
        return Arr::get($_SESSION, $key, $default);
    }

    /**
     * See if a key exists within session data using javascript dot notation
     *
     * @param    string     Session data key
     * @returns  boolean    True if found
     */
    public function has($key)
    {
        return Arr::has($_SESSION, $key);
    }

    /**
     * Assign session a new session id.
     *
     * @todo  Create safe session rotation method, for when data has already been
     *        sent to the browser.
     *
     * @param     boolean     Execute rotation safely
     * @return boolean True if rotation is successful
     */
    public function rotate()
    {
        return session_regenerate_id(true);
    }

    /**
     * Write an item to the session data using javascript dot notation
     *
     * @param     string     Session data key
     * @param     mixed      Data to write to session
     * @returns   void
     */
    public function set($key, $data)
    {
        Arr::set($_SESSION, $key, $data);
    }

    /**
     * Magic remover
     *
     * @see    \Nerd\Session::delete()
     */
    public function __unset($key)
    {
        return $this->delete($key);
    }

    /**
     * Magic getter
     *
     * @see    \Nerd\Session::get()
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magic checker
     *
     * @see    \Nerd\Session::has()
     */
    public function __isset($key)
    {
        return $this->has($key);
    }

    /**
     * Magic setter
     *
     * @see    \Nerd\Session::set()
     */
    public function __set($key, $data)
    {
        $this->set($key, $data);
    }

    /**
     * Class Destructor
     *
     * Writes flash into base session data and writes session to storage
     *
     * @returns    void
     */
    public function __destruct()
    {
        $this->triggerEvent('session.close', [$this]);
        session_write_close();
    }

    /**
     * {@inheritdoc}
     */
    public function __sleep()
    {
        $this->triggerEvent('session.sleep', [$this]);

        return $_SESSION;
    }
}
