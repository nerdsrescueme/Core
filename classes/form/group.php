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
 * HTML Group Class
 *
 * Creates a recursable group container object. this object is meant to bundle
 * several field objects into one container. This is useful for checkbox and radio
 * groups.
 *
 * [!!] This will automatically strip wrappers from the object, as to not create an
 *      overly complex html object that needs a ton of rendering magic.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Group extends Container
{
	public $element = 'div';

	public function field(Field $field, array $options = [])
	{
		$this->fields->add($field->wrap(false)->wrapField(false));
		return $field;
	}

	public function render()
	{
		if ($this->removed)
		{
			return '';
		}

		$start = $end = null;

		if ($this->hasWrap())
		{
			list($start, $end) = $this->wrap;
		}

		$out  = $start;
		$out .= ($this->label ?: '');
		$out .= "<{$this->element}{$this->attributes(true)}>";

		$this->fields->each(function($field) use (&$out)
		{
			$out .= (string) $field->render().' '; // space is important for visuals?
		});

		$out .= "</{$this->element}>";
		$out .= $end;

		return $out;
	}
}