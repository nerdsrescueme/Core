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
 * Form Textarea Class
 *
 * @package    Nerd
 * @subpackage Form
 */
class Textarea extends Input
{
    /**
     * Extended allowed field attributes
     *
     * @see Nerd\Design\Attributable
     * @var array
     */
    protected static $localAttributes = ['form'];

    /**
     * Attributes placeholder
     *
     * @var array
     */
    protected static $attributes;

    /**
     * Button text
     *
     * @var string
     */
    public $text;

    /**
     * Add text to this button object
     *
     * @param    string          Text to be displayed
     * @return Button
     */
    public function text($text)
    {
        $this->text = trim($text);

        return $this;
    }

    /**
     * Render this element
     *
     * @return string Rendered button
     */
    public function render()
    {
        if ($this->removed) {
            return '';
        }

        $start = $end = $fieldStart = $fieldEnd = '';

        if ($this->hasWrap()) {
            list($start, $end) = $this->wrap;
        }

        if ($this->hasFieldWrap()) {
            list($fieldStart, $fieldEnd) = $this->fieldWrap;
        }

        return $start
             . (isset($this->label) ? $this->label : '')
             . $fieldStart
             . "<textarea{$this->attributes(true)}>{$this->text}</textarea>"
             . $fieldEnd
             . $end;
    }
}
