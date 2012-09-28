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
 * Specification pattern
 *
 * The specification pattern is a particular software design pattern, whereby
 * business rules can be recombined by chaining the buisness rules together
 * using boolean logic. A specification pattern outlines a business rule that is
 * combined with other business rules. In this pattern, a unit of business logic
 * inherits its functionality from the abstract aggregate Composite
 * Specification class. the Composite Specification class has one function
 * called `isSatisfiedBy` that results a boolean value. After instantiation, the
 * specification is "chained" with other specifications, making new
 * specifications easily maintainable, yet highly customizable business logic.
 * Furthermore upon instantiation the business logic may, through method
 * invokation or inversion of control, have its state altered in order to become
 * a delegate of other classes such as a persistence repository.
 *
 * @package    Nerd
 * @subpackage Design
 */
interface Specification {

	/**
	 * @return   boolean          Returns true or false
	 */
	public function isSatisfiedBy($candidate);
}