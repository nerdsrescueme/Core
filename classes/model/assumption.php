<?php
/**
 * Model Namespace
 *
 * Describe me
 *
 * @package Core
 * @subpackage Model
 */
namespace Nerd\Model;

// Alias rules
use \Nerd\Model\Column;

/**
 * Abstract Model Assumption Class
 *
 * @package Core
 * @subpackage Model
 */
abstract class Assumption {

	/**
	 * Column instance
	 *
	 * @var \Nerd\Model\Column
	 */
	public $column;

	/**
	 * Class Constructor
	 *
	 * @param    \Nerd\Model\Column       Column instance
	 * @return   \Nerd\Model\Assumption   Assumption instance
	 */
	public function __construct(Column $column, $secondary = null)
	{
		$this->column = $column;
	}

	/**
	 * Abstract check method
	 *
	 * Performs the data check on the column value.
	 *
	 * @param    mixed      Value assigned to the column on the model instance
	 * @return   boolean    Did the value check succeed?
	 */
	abstract function check($value);

	/**
	 * Abstract modifier method
	 *
	 * @param    mixed      Value assigned to the column on the model instance
	 * @return   mixed      Modified value
	 */
	public function modify($value)
	{
		return $value;
	}

	/**
	 * Get the error text for this assumption.
	 *
	 * @return   string     Error text
	 */
	abstract function errorText();
}