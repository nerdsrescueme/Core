<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Autoloader class
 *
 * The Autoloader class provides a simplified approach to autoloading classes,
 * while still retaining PSR-0 technical interoperability. With the exception of
 * itself, this Autoloader serves as the primary class loader throughout the
 * library.
 *
 * ## Usage
 *
 * The Autoloader is automatically registered to the SPL autoload stack by the
 * bootstrap process. Assuming you include that file, this class will work
 * transparently, and you don't need to worry about any of the functions!
 *
 * @package Nerd
 * @subpackage Core
 */
class Autoloader
{
	/**
     * Removes the namespace from a given class string, leaving you with just the
     * class name without its namespace.
     *
     * @param    string           Full class name with namespace
     * @return string Class name without namespace
     */
    public static function denamespace($class)
    {
        $class = explode('\\', $class);

        return array_pop($class);
    }

    /**
     * Adds the Autoloader to the SPL autoloader stack
     *
     * @return boolean Returns true on success, otherwise false
     */
    public function register($prepend = false)
    {
        return spl_autoload_register([$this, 'load'], true, $prepend);
    }

    /**
     * Removes the Autoloader from the SPL autoloader stack.
     *
     * @return boolean Returns true on success, otherwise false
     */
    public function unregister()
    {
        return spl_autoload_unregister([$this, 'load']);
    }

    /**
     * Autoloads a class, interface or trait. When called by the SPL autoload stack,
     * the failure of this method results in an exception being thrown.
     *
     * @param    string           The class or interface
     * @return boolean Returns true if the class was loaded, otherwise false
     */
    public function load($name)
    {
		if ($this->exists($name)) {
			return true;
		}

		$position  = strpos($name, '\\');
		$namespace = '';
		$class     = $name;

        if ($position !== false) {
            $namespace .= substr($name, 0, $position);
            $class      = substr($name, ($position + 1));
        }

        $path = join(DS, [\Nerd\LIBRARY_PATH, strtolower($namespace), ucfirst($namespace), str_replace('\\', DS, "{$class}.php")]);

        if (!is_file($path)) {
            return false;
        }

		include($path);

		$interfaces = class_implements($name, false);
		if (in_array('Nerd\Design\Initializable', $interfaces)) {
			$name::__initialize();
		}

        return $this->exists($name);
    }

    /**
     * Check whether a class or interface exists, without attempting to autoload
     * it.
     *
     * @param    string           The class or interface
     * @return boolean Returns true if the class or interface exists, otherwise false
     */
    public function exists($name)
    {
        return class_exists($name, false)
		    or interface_exists($name, false)
			or trait_exists($name);
    }
}
