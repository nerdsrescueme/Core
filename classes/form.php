<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Form Class
 *
 * Object oriented form builder class. This class is the gateway to Nerd's form
 * building capabilities. In essence, it creates an enumerable and renderable instance
 * of a form built with various HTML classes.
 *
 * @todo  Explain more when finished
 * 
 * @package    Nerd
 * @subpackage Core
 */
class Form
{
	// Traits
	use Design\Eventable
	  , Design\Attributable
	  , Design\Renderable
	  , Design\Wrappable;

	/**
	 * Options that should be considered an attribute. This array corresponds to the
	 * attributes configuration file.
	 *
	 * @var array
	 */
	protected static $localAttributes = ['form'];

	/**
	 * Enumerable array of form fields (recursive)
	 *
	 * @var array
	 */
	public $fields;

	/**
	 * Class Cosntructor
	 *
	 * Create a form and assign options/attributes to it.
	 *
	 * @param    array          Array of form options
	 * @return   Form
	 */
	public function __construct(array $options = [])
	{
		$this->fields = new \Nerd\Design\Collection();

		foreach ($options as $key => $value)
		{
			$this->option($key, $value);
		}
	}

	/**
	 * Locate form elements by type of field
	 *
	 * ## Usage
	 *
	 *     $hiddenFields = $form->findByType('hidden');
	 *     $dateFields   = $form->findByType('date');
	 *
	 * @param    string          Field type
	 * @return   mixed           Field or array of fields
	 */
	public function findByType($type)
	{
		$fields = $this->fields->findAllRecursively(
			function($field) use ($type)
			{
				$class = Autoloader::denamespace(get_class($field));
				return !$field->removed and $class === ucfirst($type);
			},
			function($field)
			{
				if ($field instanceof Form\Container)
				{
					return $field->fields->toArray();
				}
			}
		);

		return count($fields) === 1 ? $fields[0] : $fields;
	}

	/**
	 * Locate form elements by one of its attributes
	 *
	 * ## Usage
	 *
	 *     $fields      = $form->findByAttribute('value', 1);
	 *     $smallFields = $form->findByAttribute('max', 3);
	 *
	 * @param    string          Field type
	 * @return   mixed           Field or array of fields
	 */
	public function findByAttribute($attribute, $value)
	{
		$fields = $this->fields->findAllRecursively(
			function($field) use ($attribute, $value)
			{
				return !$field->removed and isset($field->{$attribute}) and $field->{$attribute} === $value;
			},
			function($field)
			{
				if ($field instanceof Form\Container)
				{
					return $field->fields->toArray();
				}
			}
		);

		return count($fields) === 1 ? $fields[0] : $fields;
	}

	/**
	 * Add a new field to the form
	 *
	 * This will add a field to the form of the given type. Types are defined within
	 * the Nerd library, but will be searched for in the current application if none
	 * is found. The field can be appended or prepended to the form, or simply created
	 * and not added to the form.
	 *
	 * ## Usage
	 *
	 *     $form->field('hidden', ['value' => 1]); // Append a hidden field
	 *     $form->field('text', ['class' => 'small'], true); // Prepend a text field
	 *
	 *     $field = $form->field('text', [], null); // Only create an element
	 *
	 * @param    string          Type of field to add
	 * @param    array           Field options
	 * @param    boolean|null
	 */
	public function field($type, array $options = [], $prepend = false)
	{
		if (strpos($type, '\\') == false)
		{
			$type = '\\Nerd\\Form\\Field\\'.ucfirst($type);
		}

		if (!class_exists($type))
		{
			throw new \InvalidArgumentException("Form field type [$type] does not exist.");
		}

		$field = new $type($options);

		if ($this->hasWrap())
		{
			$field->wrap($this->wrap[0], $this->wrap[1]);
		}

		if ($this->hasFieldWrap())
		{
			$field->wrapField($this->fieldWrap[0], $this->fieldWrap[1]);
		}

		if ($prepend !== null)
		{
			$this->fields->add($field, $prepend);
		}

		return $field;
	}

	/**
	 * Add a fieldset to the form
	 *
	 * Create a fieldset container within the form. A fieldset object is built to be
	 * recursable by the form renderer, allowing for maximum form building flexibility.
	 * This method optionally takes fields as arguments, adding them to the fieldset
	 * upon creation
	 *
	 * @return    Nerd\Form\Fieldset
	 */
	public function fieldset()
	{
		$fields   = func_get_args();
		$fieldset = new Form\Fieldset();

		foreach($fields as $field)
		{
			$fieldset->field($field);
		}

		$this->fields->add($fieldset);

		return $fieldset;
	}

	/**
	 * Add a fieldset to the form
	 *
	 * Create a fieldset container within the form. A group object is built to be
	 * recursable by the form renderer.
	 *
	 * @return    Nerd\Form\Group
	 */
	public function group()
	{
		$fields = func_get_args();
		$group  = new Form\Group();

		if ($this->hasWrap())
		{
			$group->wrap($this->wrap[0], $this->wrap[1]);
		}

		if ($this->hasFieldWrap())
		{
			$group->wrapField($this->fieldWrap[0], $this->fieldWrap[1]);
		}

		foreach($fields as $field)
		{
			$group->field($field);
		}

		$this->fields->add($group);

		return $group;
	}

	/**
	 * Add a container element to the form
	 *
	 * Create a container within the form. A container object is built to be
	 * recursable by the form renderer, allowing for maximum form building flexibility.
	 * This method optionally takes fields as arguments, adding them to the container
	 * upon creation
	 *
	 * @return    Nerd\Form\Container
	 */
	public function container()
	{
		$fields    = func_get_args();
		$start     = array_shift($fields);
		$end       = array_shift($fields);
		$container = new Form\Html($start, $end);

		foreach($fields as $field)
		{
			$container->field($field);
		}

		$this->fields->add($container);

		return $container;
	}

	/**
	 * Render this form to HTML markup
	 *
	 * @return    string          Rendered form
	 */
	public function render()
	{
		$out = "<form{$this->attributes(true)}>";

		if (Config::get('form.csrf.enabled', false))
		{
			$state = md5(serialize($this));
			$this->triggerEvent('form.csrf', [$this, $state]);
			$out .= $this->field('hidden', ['name' => '@@state', 'value' => $state], null);
		}

		$this->fields->each(function($field) use (&$out)
		{
			if ($field instanceof Form\Container)
			{
				$out .= (string) $field->render();
				return;
			}

			$out .= (string) $field->render();
		});

		return $out . '</form>';
	}
}