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
 * Form Button Class
 *
 * [!!] Form buttons are treated like input elements with a text property used to
 *      render the button text instead of using the value attribute.
 *
 * @package    Nerd
 * @subpackage Form
 */
class Button extends Input
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

        return "<button{$this->attributes(true)}>{$this->text}</button>";
    }
}
