<?php

namespace Nerd\Model\Assumption;

use \Nerd\Model\Column;

class Max extends \Nerd\Model\Assumption
{
    private $_constraint;
    private $_type = 'integer';

    public function __construct(Column $column, $constraint = null)
    {
        parent::__construct($column);

        if ($column->is(Column::TYPE_STRING)) {
            $this->_type = 'string';
        }

        $this->_constraint = (int) $constraint;
    }

    public function check($value)
    {
        return $this->_type == 'string'
            ? strlen((string) $value) <= $this->_constraint
            : (int) $value <= $this->_constraint;
    }

    public function errorText()
    {
        return $this->_type == 'string'
            ? "%s cannot be more than {$this->_constraint} characters long"
            : "%s cannot be more than {$this->_constraint}";
    }
}
