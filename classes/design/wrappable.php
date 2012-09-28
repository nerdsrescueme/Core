<?php

/**
 * Design namespace. This namespace is meant to provide abstract concepts and in
 * most cases, simply interfaces that in someway structures the general design
 * used in core components. Additionally, the Design namespace provides sub
 * namespaces that relate specifically to common design patterns that can be
 * attached to classes without duplication of functionality.
 *
 * @package Nerd
 * @subpackage Design
 */
namespace Nerd\Design;

trait Wrappable
{
	protected $wrap;
	protected $fieldWrap;

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

	public function wrapFields($false)
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
}