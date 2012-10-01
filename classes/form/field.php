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

// Aliasing rules
use Nerd\Arr;
use Nerd\Form;

/**
 * Abstract Field Class
 *
 * Base class for form fields. This provides common functionality for all form
 * elements. Form field types *must* extend this class in order to be rendered by
 * the various rendering classes.
 *
 * @package    Nerd
 * @subpackage Form
 */
abstract class Field
{
	// Traits
	use \Nerd\Design\Attributable
	  , \Nerd\Design\Wrappable;

	private static $localAttributes = ['form'];

	public $label;
	public $removed = false;

	public function __construct(array $options = [])
	{
		foreach ($options as $key => $value)
		{
			$this->option($key, $value);
		}
	}

	public function label($text = null, array $options = [])
	{
		if ($text === null)
		{
			return $this->label ?: false;
		}

		$this->label = new Label($text, $options, $this);
		return $this;
	}

	public function wrap($false)
	{
		if ($false === false)
		{
			$this->wrap = null;
		}
		else
		{
			$this->wrap = func_get_args();
		}

		return $this;
	}

	public function wrapField($false)
	{
		if ($false === false)
		{
			$this->fieldWrap = null;
		}
		else
		{
			$this->fieldWrap = func_get_args();
		}

		return $this;
	}

	public function hasWrap()
	{
		return is_array($this->wrap);
	}

	public function hasFieldWrap()
	{
		return is_array($this->fieldWrap);
	}

	public function render()
	{
		if ($this->removed)
		{
			return '';
		}

		$start = $end = $fieldStart = $fieldEnd = '';

		if ($this->hasWrap())
		{
			list($start, $end) = $this->wrap;
		}

		if ($this->hasFieldWrap())
		{
			list($fieldStart, $fieldEnd) = $this->fieldWrap;
		}

		return $start
		     . (isset($this->label) ? $this->label : '')
			 . $fieldStart
		     . "<input{$this->attributes(true)}>"
			 . $fieldEnd
		     . $end;
	}

	public function remove()
	{
		$this->removed = true;
		return $this;
	}
}