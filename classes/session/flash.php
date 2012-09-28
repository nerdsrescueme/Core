<?php

/**
* Session namespace
*
* @package Nerd
* @subpackage Session
*/
namespace Nerd\Session;

// Aliasing rules
use \Nerd\Arr;

/**
* Flash Class
*
* The Flash class is used by the session class to enable the application
* to send messages across two requests easily.
*
*     // Usage through Nerd\Session
*     Session::instance()->flash->has('mykey');
*     
*     Flash::instance()->get('mykey');
*
* @package Nerd
* @subpackage Session
*/
class Flash
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * Get an item from flash data
	 *
	 * @param     string     Flash data key
	 * @param     mixed      Data to return if flash item is not found
	 * @returns   mixed      Flash data value or default
	 */
	public function get($key, $default = null)
	{
		if($value = Arr::get($_SESSION, "flash.$key"))
		{
			if(Arr::get($value, 'aged', false))
			{
				Arr::delete($_SESSION, "flash.$key");
				return $default;
			}
			
			Arr::set($_SESSION, "flash.$key.aged", true);
			
			return Arr::get($value, 'value');
		}

		return $default;
	}

	/**
	 * Check if a flash item exists using javascript dot notation
	 *
	 * @param     string     Flash data key
	 * @returns   boolean    True if key exists
	 */
	public function has($key)
	{
		return Arr::has($_SESSION, "flash.$key");
	}

	/**
	 * Tell flash class not to delete flash item
	 *
	 * @param     string     Flash data key
	 * @returns   void
	 */
	public function keep($key)
	{
		Arr::set($_SESSION, "flash.$key.aged", false);
	}

	/**
	 * Set an item to flash data using javascript dot notation
	 *
	 * @param     string     Flash data key
	 * @param     mixed      Data to write to flash
	 * @return    void
	 */
	public function set($key, $data)
	{
		Arr::set($_SESSION, "flash.$key", array('aged' => false, 'value' => $data));
	}

	/**
	 * Delete flash data using javascript dot notation
	 *
	 * @param    string     Flash data key
	 * @returns  void
	 */
	public function delete($key)
	{
		Arr::delete($_SESSION, "flash.$key");
	}
}