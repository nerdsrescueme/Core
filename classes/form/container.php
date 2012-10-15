<?php

/**
 * Nerd Form Namespace
 *
 * The form namespace contains elements pertaining to the form builder classes. They
 * support the Form class by providing elements and extentions to the main class
 * allowing for OOP creation of HTML forms that can be altered all the way to the
 * on-demand rendering of the form.
 *
 * @package    Nerd
 * @subpackage Form
 */
namespace Nerd\Form;

/**
 * Abstract Container Class
 *
 * Used to create HTML form container objects. These objects allow for fields to
 * exist within "containing" form elements, be searched and rendered recursively
 * on-demand.
 *
 * @package    Nerd
 * @subpackage Form
 */
abstract class Container extends Field
{
    public $fields;
    protected $element;

    public function __construct()
    {
        $this->fields = new \Nerd\Design\Collection(func_get_args());
    }

    public function field(Field $field, array $options = [])
    {
        $this->fields->add($field);

        return $field;
    }
}
