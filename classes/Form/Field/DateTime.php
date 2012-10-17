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
 * Form Datetime Field Class
 *
 * [!!] Datetime fields automatically convert dates into the format required by the
 *      HTML5 Datetime field specification.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Datetime extends Input
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
            // For datetime inputs date must be: yyyy-mm-ddThh:mm:ss+00:00
            $value = date('c', strtotime($value));
        }

        return parent::option($option, $value);
    }
}
