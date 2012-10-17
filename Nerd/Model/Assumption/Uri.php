<?php

namespace Nerd\Model\Assumption;

class Uri extends \Nerd\Model\Assumption
{
	public function check($value)
	{
		return true;
	}

	public function modify($value)
	{
		return $value;
	}

	public function errorText()
	{
		return "%s must be a valid URI";
	}
}
