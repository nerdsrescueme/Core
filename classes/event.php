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
 * Event class
 *
 * The event class provides Nerd-wide access to events. It's not implemented to be
 * a complete event driven architecture, but to provide a unified way to handle
 * events within the framework, without the need to over-ride every class. Typically,
 * classes will use the \Nerd\Design\Eventable trait to provide access to this class.
 * This binds the bindEvent(), unbindEvent() and triggerEvent() methods to the class
 * allowing it to handle different event scenarios.
 * 
 * [!!] The current implementation is subject to change at any time, since its
 *      implementation is not exactly "standard". However, at this time we feel that
 *      it provides the "best of both worlds" in terms of event-driven and request-
 *      driven programming.
 * 
 * # Adding events to your classes
 *
 *     class MyClass {
 *         use \Nerd\Design\Eventable;
 *     }
 *
 * # Binding an event without the Eventable trait
 *
 *     Event::instance()->bind('namespace.event', function($arg1)
 *     {
 *         // Do something...
 *     })
 *
 * # Triggering an event without the Eventable trait
 *
 *     Event::instance()->trigger('namespace.event', ['arg1', 'arg2']);
 *
 * @see        Nerd\Design\Eventable
 * @package    Nerd
 * @subpackage Core
 */
class Event
{
	// Traits
	use Design\Creational\Singleton
	  , Design\Dotparser;

	/**
	 * Events and their corresponding functions
	 *
	 * @var array
	 */
	private $events = [];

	/**
	 * Loaded events
	 *
	 * @var array
	 */
	private $loaded = [];

	/**
	 * Load an event from the file system
	 *
	 * Attempts to load an event file from the filesystem. When specifying your event
	 * key to load, it *must* be prefixed by the main application namespace. In most
	 * cases, this will be "application". In multi-application setups, this could be
	 * any of the folders within the LIBRARY_PATH folder.
	 *
	 * @param    string          Dot notated event to load
	 * @return   boolean         Was the file loaded?
	 */
	private function load($key)
	{
		list($package, $file, $event) = static::parse($key);

		$events = include \Nerd\LIBRARY_PATH.DS.$package.'/events/'.$file.'.php';
		$this->loaded[] = $file;

		if ($events === false)
		{
			return false;
		}

		foreach ($events as $event => $function)
		{
			$this->bind($event, $function);
		}

		return true;
	}

	/**
	 * Trigger an event
	 *
	 * This method will trigger an event defined in the $events array. If non can be
	 * found, it will attempt to load the events for the event namespace (text before
	 * the first ".")
	 *
	 * ## Usage
	 *
	 *     Event::instance()->trigger('view.render', array($viewInstance));
	 *
	 * 
	 * @param    string          Event name with namespace
	 * @param    array           Arguments to pass to the event function
	 * @return   boolean         Were we able to execute any functions?
	 */
	public function trigger($key, array $args = [])
	{
		if (isset($this->events[$key]))
		{
			foreach ($this->events[$key] as $func)
			{
				call_user_func_array($func, $args);
			}

			return true;
		}
		else
		{
			list($package, $file, $event) = static::parse($key);

			if (!in_array($file, $this->loaded) and $this->load($file))
			{
				if ($this->trigger($key, $args))
				{
					return true;
				}
			}

			return false;
		}
	}

	/**
	 * Bind an event
	 *
	 * This method will bind an event to the events class to be called at a later
	 * time.
	 *
	 * ## Usage
	 *
	 *     Event::instance()->bind('view.myevent', function($arg1)
	 *     {
	 *         // Do something...
	 *     })
	 *
	 * @param    string          Event name with namespace
	 * @param    callable        Function to execute
	 * @return   void
	 */
    public function bind($event, callable $func)
    {
			return $this->events[$event][] = $func;
    }

	/**
	 * Unind an event
	 *
	 * Unbind an event from the events array and return whether or not the operation
	 * was successful.
	 *
	 * ## Usage
	 *
	 *     Event::instance()->unbind('view.myevent');
	 *
	 * @param    string          Event name with namespace
	 * @return   boolean
	 */
	public function unbind($event)
	{
		if (isset($this->events[$event]))
		{
			unset($this->events[$event]);
			return true;
		}

		return false;
	}
}