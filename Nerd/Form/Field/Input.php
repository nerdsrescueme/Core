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
 * Abstract Form Input Field Class
 *
 * Provides basic functionality for form input elements.
 *
 * @package    Nerd
 * @subpackage Form
 */
abstract class Input extends \Nerd\Form\Field
{
    /**
     * Extended allowed field attributes
     *
     * @see Nerd\Design\Attributable
     * @var array
     */
    protected static $localAttributes = ['event.form', 'form.input', 'form.html5'];


    /**
     * Class Constructor overload
     *
     * Perform basic instantiation, but automatically assign the type attribute to
     * the name of the extended form class.
     *
     * @param    array          Field options/attributes
     * @return Input
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        $class = explode('\\', get_called_class());
        $class = strtolower(end($class));

        $this->option('type', $class);
    }
}
