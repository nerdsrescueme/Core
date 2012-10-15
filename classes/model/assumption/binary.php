<?php

namespace Nerd\Model\Assumption;


class Binary extends \Nerd\Model\Assumption
{
    public function check($boolean)
    {
        return is_bool($boolean);
    }

    public function modify($value)
    {
        return (bool) $value;
    }

    public function errorText()
    {
        return "%s must contain a true/false value";
    }
}
