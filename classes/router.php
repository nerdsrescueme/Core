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
 * Router class
 *
 * Explain with detail
 *
 * @package    Nerd
 * @subpackage Core
 */
class Router implements Design\Initializable
{
	/**
	 * Default controller namespace
	 *
	 * @var    string
	 */
	private static $defaultNamespace;

	/**
	 * Allow MVC routing
	 *
	 * @var    boolean
	 */
	public static $mvc = true;

	/**
	 * Available MVC'd packages
	 *
	 * @var    array
	 */
	private static $packages = [];

	/**
	 * Available replacement patterns
	 *
	 * @var    array
	 */
	private static $patterns = [

		'(:num)'     => '([0-9]+)',
		'/(:num?)'   => '(?:/([0-9]+))',
		'(:any)'     => '([a-zA-Z0-9\.\-_]+)',
		'/(:any?)'   => '(?:/([a-zA-Z0-9\.\-_]+))',
	];

	/**
	 * Cached collection of loaded routes
	 *
	 * @var    array
	 */
	private static $routes = [];

	/**
	 * Initialize the Router class
	 *
	 * @todo     Load APPLICATION_NS's routes by default…
	 * 
	 * @return   void     No value is returned
	 */
	public static function __initialize()
	{
		static::$defaultNamespace = '\\'.ucfirst(APPLICATION_NS).'\\Controller\\';
		
		static::registerPackage(APPLICATION_NS);
	}

	/**
	 * Create a route connection
	 *
	 * Explain in detail
	 *
	 * @param    string     Route path
	 * @param    array      Route options
	 * @return   void
	 */
	public static function connect($route, array $options = [])
	{
		preg_match("/^([A-Z]+)?\s?([^\\s]+)$/us", $route, $match);

		if(count($match) === 0)
		{
			throw new \InvalidArgumentException('Invalid route provided ['.$route.']');
		}

		// Define the route itself
		$route = [];
		
		list($route['full'], $route['method'], $route['uri']) = $match;

		// Fill in some values if needed
		if(empty($route['method']))
		{
			$route['method'] = 'ACTION';
			$route['full']   = 'ACTION '.$route['full'];
		}

		$class = Arr::get($options, 'class');

		if(substr($class, 0, 1) != '\\')
		{
			$class = static::$defaultNamespace.$class;
		}

		$route = $route + [
			'class'  => $class,
			'action' => Arr::get($options, 'action', 'index'),
			'format' => Arr::get($options, 'format'),
			'params' => Arr::get($options, 'params')
		];

		// Create regex route path if needed
		$regex = $route['uri'];

		if(strpos($regex, '(:') !== false)
		{
			foreach(static::$patterns as $key => $pattern)
			{
				$regex = str_replace($key, $pattern, $regex);
			}
		}

		static::$routes[$regex] = $route;
	}

	/**
	 * Route Finder
	 *
	 * Match a given URI to a loaded route
	 *
	 * @param    string     URI to match
	 * @return   array      Route if a match is found
	 * @return   boolean    False if no route matched
	 */
	public static function find($uri)
	{
		foreach(static::$routes as $pattern => $route)
		{
			preg_match("#^$pattern$#", rtrim($uri, '/'), $match);

			if(count($match) > 0)
			{
				// Set named params
				if($route['params'] !== null)
				{
					$i = 0 and array_shift($match);

					foreach($route['params'] as $key => $param)
					{
						if(isset($match[++$i]))
						{
							$route['params'][$param] = $match[$i];
							unset($route['params'][$key]);
						}
					}
				}

				// Add pattern to route array
				return $route + array('pattern' => $pattern);
			}
		}

		return static::$mvc ? static::findMVC($uri) : false;
	}

	public static function findMVC($uri)
	{
		$segments = explode('/', trim($uri, '/'));
		$package  = array_shift($segments);

		$path  = LIBRARY_PATH.'/'
		       . (isset(static::$packages[$package]) ? $package : strtolower(APPLICATION_NS))
		       . '/classes/controller/';
		$class = '\\'
		       . (isset(static::$packages[$package]) ? ucfirst($package) : ucfirst(APPLICATION_NS))
		       . '\\Controller\\';

		if(!isset(static::$packages[$package]))
		{
			array_unshift($segments, $package);
			$package = APPLICATION_NS;
		}

		$current = array_shift($segments);
		
		while(is_dir($path.$current))
		{
			$path  .= strtolower($current).'/';
			$class .= ucfirst($current).'\\';
			
			$current = array_shift($segments);
		}

		$response = \Nerd\Http\Response::make();
		$action   = array_shift($segments);

		if(file_exists($path.$current.'.php'))
		{
			$class .= ucfirst($current);

			try {
				$controller = new $class();
			}
			catch(\Exception $e)
			{
				throw new \Nerd\Http\Exception(500, $response);
			}

			$action = 'action'.ucfirst($action ?: 'index');

			if(!method_exists($controller, $action))
			{
				throw new \Nerd\Http\Exception(404);
			}

			$controller->before();
			$response->setBody(call_user_func_array(array($controller, $action), $segments));
			$controller->after();

			return $response;
		}

		throw new \Nerd\Http\Exception(404, $response);
	}

	/**
	 * Register the Default Namespace
	 *
	 * The default namespace will be prepended to every non-fully namespaced path
	 * given as the class property of each route.
	 *
	 * @param    string     Default Controller Namespace
	 * @return   void
	 */
	public static function registerDefaultNamespace($namespace)
	{
		static::$defaultNamespace = $namespace;
	}

	public static function registerPackage($package)
	{
		static::$packages[strtolower($package)] = ucfirst($package);
	}

	/**
	 * Register a Replacement Pattern
	 *
	 * @param    string     URI token to match
	 * @param    string     Regex pattern to replace token with
	 * @return   void
	 */
	public static function registerPattern($token, $pattern)
	{
		static::$patterns[$token] = $pattern;
	}
}