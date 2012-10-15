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
 * Form Select Field Class
 *
 * Unlike most other form elements, the select object inherits directly from the
 * abstract Field class. This element has many special characteristics that make it
 * difficult to wrap up with other form elements, so it's treated differently.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Select extends \Nerd\Form\Field
{
    /**
     * Extended allowed field attributes
     *
     * @see Nerd\Design\Attributable
     * @var array
     */
    protected static $localAttributes = ['event.form', 'form'];

    /**
     * Attributes placeholder
     *
     * @var array
     */
    protected static $attributes;

    /**
     * Render this element
     *
     * @return string Rendered select field
     */
    public function render()
    {
        if ($this->removed) {
            return '';
        }

        $start = $end = $startField = $endField = '';

        if ($this->hasWrap()) {
            list($start, $end) = $this->wrap;
        }

        if ($this->hasFieldWrap()) {
            list($startField, $endField) = $this->fieldWrap;
        }

        $options  = (array) $this->option('options');
        $optstr   = '';
        $selected = $this->option('selected');

        foreach ($options as $opt => $label) {
            // Support for optgroups.
            if (is_array($label)) {
                $optstr .= "<optgroup label=\"$opt\">";

                foreach ($label as $opt2 => $label2) {
                    $label2 = trim($label2, "'");

                    if ((string) $opt2 === (string) $selected) {
                        $optstr .= "<option value=\"$opt2\" selected>$label2</option>";
                    } else {
                        $optstr .= "<option value=\"$opt2\">$label2</option>";
                    }
                }

                $optstr .= '</optgroup>';
                continue;
            }

            $label = trim($label, "'");

            if ((string) $opt === (string) $selected) {
                $optstr .= "<option value=\"$opt\" selected>$label</option>";
            } else {
                $optstr .= "<option value=\"$opt\">$label</option>";
            }
        }

        return $start
             . (isset($this->label) ? $this->label : '')
             . $startField
             . "<select {$this->attributes(true)}>{$optstr}</select>"
             . $endField
             . $end;
    }
}
