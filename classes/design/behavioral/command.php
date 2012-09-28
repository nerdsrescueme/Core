<?php

/**
 * Behavioral design pattern namespace.
 *
 * Behavior design patterns are design patterns that identify common
 * communication patterns between objects and realize these patterns. By doing
 * so, these patterns increase flexibility in carrying out this communication.
 *
 * @package    Nerd
 * @subpackage Design
 */
namespace Nerd\Design\Behavioral;

/**
 * Command pattern
 *
 * The command pattern is a design pattern in which an object is used to
 * represent and encapsulate all information needed to call a method at a later
 * time. This information includes the method name, the object that owns the
 * method and values for the method parameters.
 *
 * Three terms always associated with the command pattern are *client*,
 * *invoker* and *reciever*. The *client* instantiates the command object and
 * provides the information required to call the method at a later time. The
 * *invoker* decides when the method should be called. The *reciever* is an
 * instance of the class that contains the method's code.
 *
 * Using command objects makes it easier to construct general components that
 * need to delegate, sequence or execute method calls at the time of their
 * choosing without the need to know the owner of the method or the method
 * parameters.
 *
 * @package    Nerd
 * @subpackage Design
 */
abstract class Command {

	/**
	 * The object commanded by this class
	 *
	 * @var    object
	 */
	protected $comandee;

	/**
	 * Class constructor
	 *
	 * @return   void             No value is returned
	 */
	public function __construct($comandee = null)
	{
		$this->comandee = $commandee;
	}

	/**
	 * Magic class to function method
	 * 
	 * Calls the child execute method upon invoke
	 *
	 * @return   mixed            Undefined
	 */
	public function __invoke()
	{
		return call_user_func(array($this, 'execute'), func_get_args());
	}

	// Implied method: execute
	abstract public function execute();
}