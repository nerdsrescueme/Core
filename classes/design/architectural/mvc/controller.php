<?php

/**
 * Model-view-controller design pattern namespace
 *
 * Model-view-controller (MVC) is a software architecture, currently considered
 * an architectural pattern used in software engineering. The pattern isolates
 * "domain logic" (the application logic for the user) from the user inferface
 * (input and presentation), permitting independent development, testing and
 * maintenance of each (separation of concerns).
 *
 * Model View Controller (MVC) pattern creates applications that separate the
 * different aspects of the application (input logic, business logic, and UI
 * logic), while providing a loose coupling between these elements.
 *
 * @package    Nerd
 * @subpackage MVC
 */
namespace Nerd\Design\Architectural\MVC;

/**
 * Controller abstract class
 *
 * In MVC, the controller recieves user input and initiates a response by making
 * calls on model objects. A controller accepts input from the user and
 * instructs the model and view port to perform actions based on that input.
 *
 * @package   Nerd
 * @subpackage MVC
 */
abstract class Controller
{
	// Traits
	use \Nerd\Design\Eventable;

	/**
	 * The before method is called before the requested action is called. This
	 * empty function exists to allow us to skip method existance lookups for
	 * controllers.
	 *
	 * @return   void             No value is returned
	 */
	public function before()
	{
		$this->triggerEvent('controller.setup', [$this]);
	}

	/**
	 * The after method is called after the request action is called. Like the
	 * before method of this class, this exists simply to allow us to skip
	 * method existance lookups.
	 *
	 * @return   void             No value is returned
	 */
	public function after()
	{
		$this->triggerEvent('controller.teardown', [$this]);
	}
}