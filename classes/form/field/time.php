<?php

/**
 * Nerd Form Fields Namespace
 *
 * The form fields namespace provides field types to Nerd's form builder classes.
 *
 * @package    Nerd
 * @subpackage Form
 */
namespace Nerd\Form\Field;

/**
 * Form Time Field Class
 *
 * [!!] Form time fields automatically convert dates into the format required by the
 *      HTML Time field specification.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Time extends Input
{
	/**
	 * Option/Attribute assignment overload
	 *
	 * Extends the value property allowing for automatic conversion of dates
	 *
	 * @param    string          Option/Attribute to set
	 * @param    string          Value to set to this option
	 */
	public function option($option, $value = null)
	{
		if ($option == 'value')
		{
			// For date inputs date must be: hh:mm:ss
			$value = date('h:i:s', strtotime($value));
		}

		return parent::option($option, $value);
	}
}