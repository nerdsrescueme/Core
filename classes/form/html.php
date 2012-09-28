<?php

/**
 * Nerd Form Namespace
 *
 * The form namespace contains elements pertaining to the form builder classes. They
 * support the Form class by providing elements and extentions to the main class
 * allowing for OOP creation of HTML forms that can be altered all the way to the
 * on-demand rendering of the form.
 *
 * @package    Nerd
 * @subpackage Form
 */
namespace Nerd\Form;

/**
 * HTML Container Class
 *
 * Creates a recursable HTML container object.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Html extends Container
{
	protected $element;

	public function __construct()
	{
		$fields        = func_get_args();
		$this->element = array_shift($fields);
		$this->fields  = new \Nerd\Design\Collection($fields);
	}

	public function render()
	{
		$out  = ($this->label ?: '');
		$out .= "<{$this->element}{$this->attributes(true)}>";

		$this->fields->each(function($field) use (&$out)
		{
			$out .= (string) $field->render().' '; // space is important for visuals?
		});

		return $out . "</{$this->element}>";
	}
}