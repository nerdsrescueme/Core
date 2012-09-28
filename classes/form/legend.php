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
 * Legend Class
 *
 * Creates a legend fieldset form element
 *
 * @package    Nerd
 * @subpackage  Form
 */
class Legend
{
	// Traits
	use \Nerd\Design\Attributable
	  , \Nerd\Design\Renderable;

	public $text;
	private $field;

	public function __construct($text, array $options = [], Fieldset $field = null)
	{
		foreach ($options as $key => $value)
		{
			$this->option($key, $value);
		}

		$this->field = $field;
		$this->text  = trim($text);
	}

	public function render()
	{
		return "<legend{$this->attributes(true)}>{$this->text}</legend>";
	}
}