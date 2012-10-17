<?php

namespace Nerd\Model\Assumption;

class Required extends \Nerd\Model\Assumption
{
    public function check($value)
    {
        // Special condition for boolean false
        if ($value === false) {
            return true;
        }

        return !in_array($value, [null, '']);
    }

    public function errorText()
    {
        return '%s is a required field';
    }
}
