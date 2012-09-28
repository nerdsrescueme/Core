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
 * Application Class
 *
 * This class provides common, critical functionality to be shared among application
 * specific extension classes. An application being run must define its very own
 * application class extension, or some equivalent. Application objects perform
 * several tasks that are critical to your application. First, it instantiates all
 * classes required for your application. Second, it acts as a global singleton
 * registry for your application. Third, it holds common application-wide methods
 * you may use from anywhere in your codebase. Lastly, it must hold a response object
 * in order to send output to your user.
 *
 * @package Nerd
 * @subpackage Core
 */
class Application
{
	// Traits
	use Design\Creational\Singleton
	  , Design\Eventable;

	/**
	 * Redirect user
	 *
	 * @param    string          Url endpoint
	 * @param    integer         Redirect status code
	 * @return   void
	 */
	public function redirect($to = null, $status = 302)
	{
		(new \Nerd\Http\Response())->redirect($to, $status);
	}
}