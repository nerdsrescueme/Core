<?php

namespace Nerd\Model\Assumption;

use \Nerd\Model\Column;

class Options extends \Nerd\Model\Assumption
{
    public function check($value)
    {
        $options = explode(',', str_replace("'", '', $this->column->constraint));

        return in_array($value, $options);
    }

    public function errorText()
    {
        // Reads the string to make a nicer readable string.
        $list = str_replace(',', ', ', str_replace("'", '', $this->column->constraint));
        $last = strrpos($list, ', ');
        $replace = substr($list, $last, strlen($list)-$last);
        $list = str_replace($replace, str_replace(', ', ' or ', $replace), $list);

        return "%s may only be set to $list";
    }
}
