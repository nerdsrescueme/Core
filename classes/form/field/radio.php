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
 * Form Radio Field Class
 *
 * [!!] Radios are rendered differently than other input elements. The radio
 *      is rendered *inside* of the label element to support clickable labels.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Radio extends Input
{
	/**
	 * Render this element
	 *
	 * @return    string          Rendered checkbox
	 */
	public function render()
	{
		if ($this->removed)
		{
			return '';
		}

		$start = $end = $startField = $endField = '';

		if ($this->hasWrap())
		{
			list($start, $end) = $this->wrap;
		}

		if ($this->hasFieldWrap())
		{
			list($startField, $endField) = $this->fieldWrap;
		}

		$radio = "<input {$this->attributes(true)}>";

		if (isset($this->label))
		{
			$this->label->text  = "{$radio} {$this->label->text}";
		}

		return $start.$startField
		     . (isset($this->label) ? $this->label : $radio)
		     . $endField.$end;
	}
}