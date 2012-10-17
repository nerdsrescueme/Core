<?php

namespace Nerd\Model\Assumption;

class Username extends \Nerd\Model\Assumption
{
    private $_message;

    public function check($username)
    {
        if (!ctype_alnum($username)) {
            $this->_message = '%s may only use alphanumeric characters';

            return false;
        }

        if (strlen($username) < 3) {
            $this->_message = '%s must be longer than 3 characters';

            return false;
        }

        if (strlen($username) > 32) {
            $this->_message = '%s must be shorter than 32 characters';
        }

        return true;
    }

    public function errorText()
    {
        return $this->_message;
    }
}
