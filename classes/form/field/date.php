<?php

/**
 * Nerd Form Fields Namespace
 *
 * The form fields namespace provides field types to Nerd's form builder classes.
 *
 * @package    Nerd
 * @subpackage Form
 */
namespace Nerd\Form\Field;

/**
 * Form Date Field Class
 *
 * [!!] Form date fields automatically convert dates to the format required by the
 *      HTML5 date field specification.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Date extends Input
{
    /**
     * Option/Attribute assignment overload
     *
     * Extends the value property allowing for automatic conversion of dates
     *
     * @param    string          Option/Attribute to set
     * @param    string          Value to set to this option
     */
    public function option($option, $value = null)
    {
        if ($option == 'value') {
            // For date inputs date must be: yyyy-mm-dd
            $value = date('Y-m-d', strtotime($value));
        }

        return parent::option($option, $value);
    }
}
