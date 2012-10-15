<?php

namespace Nerd\Model\Assumption;

class Ip extends \Nerd\Model\Assumption
{
    public function check($ip)
    {
        return !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    public function errorText()
    {
        return '%s is not a valid IPv4 or IPv6 address (it may be a private IP)';
    }
}
