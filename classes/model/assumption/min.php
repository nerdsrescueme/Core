<?php

namespace Nerd\Model\Assumption;

use \Nerd\Model\Column;

class Min extends \Nerd\Model\Assumption {

	private $_constraint;
	private $_type = 'integer';

	public function __construct(Column $column, $constraint = null)
	{
		parent::__construct($column);

		$this->_constraint = (int) $constraint;
	}

	public function check($value)
	{
		if ($this->column->is(Column::TYPE_STRING))
		{
			$this->_type = 'string';
		}

		return strlen((string) $value) >= $this->_constraint;
	}

	public function errorText()
	{
		return $this->_type == 'string'
			? "%s must be at least {$this->_constraint} characters long"
			: "%s can not be less than {$this->constraint}";
	}
}