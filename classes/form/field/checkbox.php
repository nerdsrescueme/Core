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
 * Form Checkbox Field Class
 *
 * [!!] Checkboxes are rendered differently than other input elements. The checkbox
 *      is rendered *inside* of the label element to support clickable labels.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Checkbox extends Input
{
    /**
     * Render this element
     *
     * @return string Rendered checkbox
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

        $checkbox  = "<input {$this->attributes(true)}>";

        if (isset($this->label)) {
            $this->label->text  = "{$checkbox} {$this->label->text}";
        }

        return $start.$startField
             . (isset($this->label) ? $this->label : $checkbox)
             . $endField.$end;
    }
}
