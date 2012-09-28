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
 * Form Hidden Field Class
 *
 * @package    Nerd
 * @subpackage Form
 */
class Hidden extends Input
{
	public function hasWrap()
	{
		return false;
	}

	public function hasFieldWrap()
	{
		return false;
	}
}