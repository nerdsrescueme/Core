<?php

namespace Nerd\Model\Assumption;

class Password extends \Nerd\Model\Assumption
{
    private $_message;

    public $strength = 100;

    public function check($password)
    {
        if (strlen($password) < 6) {
            $this->strength -= 20;
            $this->_message = '%s must be longer than 6 characters';

            return false;
        }

        if (!preg_match("#[0-9]+#", $password)) {
            $this->strength -= 20;
            $this->_message = '%s must include at least 1 number';

            return false;
        }

        if (!preg_match("#[a-z]+#", $password)) {
            $this->strength -= 20;
            $this->_message = '%s must include at least 1 letter';

            return false;
        }

        if (!preg_match("#[A-Z]+#", $password)) {
            $this->strength -= 20;
            $this->_message = '%s must include at least 1 capital letter';

            return false;
        }

        if (!preg_match("#\W+#", $password)) {
            $this->strength -= 20;
            //$this->_message = '%s must include at least 1 symbol';
            //return false;
        }

        return true;
    }

    public function errorText()
    {
        return $this->_message;
    }
}
