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
 * HTML Fieldset Class
 *
 * Creates a recursable fieldset container object.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Fieldset extends Container
{
	public $element = 'fieldset';
	public $legend;

	public function legend($legend, array $options = [])
	{
		$this->legend = new Legend($legend, $options, $this);
		return $this;
	}

	public function render()
	{
		$out  = ($this->label ?: '');
		$out .= "<{$this->element}{$this->attributes(true)}>";

		if (isset($this->legend) and $this->legend !== null)
		{
			$out .= (string) $this->legend->render();
		}

		$this->fields->each(function($field) use (&$out)
		{
			$out .= (string) $field->render().' '; // space is important for visuals?
		});

		return $out . "</{$this->element}>";
	}
}