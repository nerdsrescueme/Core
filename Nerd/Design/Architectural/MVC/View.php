<?php

/**
 * Model-view-controller design pattern namespace
 *
 * Model-view-controller (MVC) is a software architecture, currently considered
 * an architectural pattern used in software engineering. The pattern isolates
 * "domain logic" (the application logic for the user) from the user inferface
 * (input and presentation), permitting independent development, testing and
 * maintenance of each (separation of concerns).
 *
 * Model View Controller (MVC) pattern creates applications that separate the
 * different aspects of the application (input logic, business logic, and UI
 * logic), while providing a loose coupling between these elements.
 *
 * @package    Nerd
 * @subpackage MVC
 */
namespace Nerd\Design\Architectural\MVC;

/**
 * View class
 *
 * In MVC, the view renders the model into a form suitable for interaction,
 * typically a user interface element. Multiple views can exist for a single
 * model for different purposes. A view port typically has one to one
 * correspondence with display surface and knows how to render to it.
 *
 * @package   Nerd
 * @subpackage MVC
 */
class View implements \Nerd\Design\Initializable
{
    // Traits
    use \Nerd\Design\Eventable
      , \Nerd\Design\Renderable;

    /**
     * The path in which views should be loaded from
     *
     * @var    string
     */
    public static $default_path = \Nerd\LIBRARY_PATH.DS.\Nerd\APPLICATION_NS.DS.'views';

    /**
     * Globally defined data available to all views
     *
     * @var    array
     */
    public static $global_data = [];


    /**
     * Add a key/value pair to the global view data.
     *
     * Bound data will be available to all views as variables.
     *
     * @param     string|array
     * @param     mixed|null
     * @return View
     */
    public static function set($keys, $value = null)
    {
        if (!is_array($keys)) {
            static::$global_data[$keys] = $value;
        } else {
            foreach ($keys as $key => $data) {
                static::$global_data[$key] = $data;
            }
        }
    }

    /**
     * The view data
     *
     * @var     array
     */
    public $data = [];

    /**
     * The name of the view
     *
     * @var     string
     */
    protected $view;

    /**
     * The view name with dots replace by slashes
     *
     * @var     string
     */
    protected $path;

    /**
     * Create a new instance of the View class
     *
     * @param     string
     * @param     array
     * @param     string
     * @return View
     */
    public function __construct($view, $data = [], $path = null)
    {
        $path === null and $path = self::$default_path;

        $this->view = $view;
        $this->data = $data;

        if (substr(strrchr($view, '.'), 1) !== false) {
            if (file_exists($this->path = $path.'/'.$view)) {
                return $this;
            }
        }

        if (!file_exists($this->path = $path.'/'.$view.'.php')) {
            throw new \InvalidArgumentException("View [{$this->path}] does not exist");
        }

        $this->triggerEvent('view.setup', [$this]);
    }

    /**
     * Magic method for getting items from the view data
     *
     * @param     string
     * @return mixed
     */
    public function __get($key)
    {
        return $this->data[$key];
    }

    /**
     * Magic method for setting items in the view data
     *
     * @param     string
     * @param     mixed
     * @return void
     */
    public function __set($key, $value)
    {
        $this->with($key, $value);
    }

    /**
     * Magic method for determining if an item is in the view data
     *
     * @param     string
     * @return boolean
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Magic method for removing an item from the view data.
     *
     * @param     string
     * @return void
     */
    public function __unset($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Add a view instance to the view data.
     *
     * @param     string
     * @param     string
     * @param     array
     * @return View
     */
    public function partial($key, $view, $data = [], $path = null)
    {
        return $this->with($key, new static($view, $data, $path));
    }

    /**
     * Add a key/value pair to the view data.
     *
     * Bound data will be available to the view as variables.
     *
     * @param     string|array
     * @param     mixed|null
     * @return View
     */
    public function with($keys, $value = null)
    {
        if (!\is_array($keys)) {
            $this->data[$keys] = $value;
        } else {
            foreach ($keys as $key => $data) {
                $this->data[$key] = $data;
            }
        }

        return $this;
    }

    /**
     * Add a key/value pair to the global view data.
     *
     * Bound data will be available to all views as variables.
     *
     * @param     string|array
     * @param     mixed|null
     * @return View
     */
    public function with_global($keys, $value = null)
    {
        static::set($keys, $value);

        return $this;
    }

    /**
     * Evaluate and render the contents of this instance
     *
     * @return string The evaluated and rendered contents
     */
    public function render()
    {
        $this->triggerEvent('view.render', [$this]);

        foreach ($this->data as &$data) {
            if ($data instanceof \Nerd\Design\Renderable) {
                $data = $data->render();
            }
        }

        ob_start();
        extract(static::$global_data, EXTR_SKIP);
        extract($this->data, EXTR_SKIP);

        try {
            include $this->path;
        } catch (\Exception $e) {
            throw new \Exception($e);
        }

        return \ob_get_clean();
    }
}
