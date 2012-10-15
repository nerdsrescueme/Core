<?php

namespace Nerd\Model\Assumption;

class Number extends \Nerd\Model\Assumption
{
    private $_message;

    public function check($number)
    {
        if ($this->column->automatic) {
            $this->_message = '%s may not be set, it is automatically set be the database';

            return false;
        }

        if ($this->column->unsigned) {
            if ((int) $number < 0) {
                $this->_message = '%s does not allow negative numbers';

                return false;
            }
        }

        $i = 0;
        $limit = '';

        while ($i < $this->column->constraint) {
            $limit .= '9';
            $i++;
        }

        if ((int) $number > (int) $limit) {
            $this->_message = '%s may not be more than '.number_format($limit);

            return false;
        }

        $this->_message = '%s does not contain a valid number';

        return !filter_var($number, FILTER_VALIDATE_INT) === false;
    }

    public function modify($value)
    {
        return (int) $value;
    }

    public function errorText()
    {
        return $this->_message;
    }
}
