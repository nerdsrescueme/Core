<?php

/**
 * Structural design pattern namespace.
 *
 * Structural design patterns are design patterns that ease the design by
 * identifying a simple way to realize relationships between entities.
 *
 * @package    Nerd
 * @subpackage Design
 */
namespace Nerd\Design\Structural;

// Aliasing rules
use Nerd\Config;

/**
 * Front Controller Pattern
 *
 * The Front controller pattern is designed for centralized request logic. A
 * front controller can help eliminate duplicate code on a series of requests
 * through the front controller and factoring the duplicate code from the
 * requests into the front controller.
 *
 * [!!] Althought MVC isn't directly related to the FrontController pattern, the
 * current implementation of this class executes it. This may change in the
 * future, but as for now, please understand what you're getting into when you
 * use this pattern.
 *
 * @package    Nerd
 * @subpackage Design
 */
class FrontController
{
    use \Nerd\Design\Creational\Singleton;

    /**
     * The dispatcher extracts the active URI and routes to a namespaced
     * Controller for further handling.
     *
     * [!!] This method could use some serious optimization, and modularity.
     *
     * ## Usage
     *
     *     $request = FrontController::dispatch();
     *
     * Once you've dispatched your request, you can handle everything after
     * as you would a \Http\Response.
     *
     * @param    string       URI to parse
     * @return Response Returns the response instance for further execution
     */
    public function dispatch($uri, \Nerd\Http\Response &$response)
    {
        $default = Config::get('routes._default_');
        $default = \explode('/', $default);

        if (($controller = ((isset($default[0]) and !empty($default[0])) ? \ucfirst($default[0]) : false)) === false) {
            throw new \Exception('Your application does not appear to have a value default route configured. Please specify one in your routes configuration file.');
        }

        $action = ((isset($default[1]) and !empty($default[1])) ? 'action'.ucfirst($default[1]) : 'actionIndex');

        unset($default);

        $directory = \Nerd\LIBRARY_PATH.\DS;
        $namespace = \Nerd\APPLICATION_NS;
        $segments  = array_merge(array_filter(explode('/', ltrim($uri, '/'))), []);

        // Determine if we're attempting to load a package or the application
        if (isset($segments[0]) and \strtolower($segments[0]) !== \strtolower($namespace) and \is_dir($directory.$segments[0])) {
            $namespace = $segments[0];
            $directory .= $segments[0].\DS;
            $segments = array_slice($segments, 1);
        } else {
            $directory .= $namespace.\DS;
        }

        $directory .= 'classes'.\DS.'controller'.\DS;
        //$response   = \Nerd\Http\Response::instance();

        if (count($segments)) {
            $possibility = [];

            while (count($segments) > 0 and is_dir($directory.$segments[0])) {
                $directory .= $segments[0].\DS;
                $possibility[] = $segments[0];
                $segments = array_slice($segments, 1);
            }

            if (count($segments) > 0) {
                if (!file_exists($directory.$segments[0].'.php')) {
                    throw new \Nerd\Http\Exception(404, $response);
                }

                $possibility[] = $segments[0];
                $segments = array_slice($segments, 1);
            }

            $controller = '';

            foreach ($possibility as $value) {
                $controller .= ucfirst($value).'\\';
            }

            $controller = rtrim($controller, '\\');

            if (count($segments) > 0) {
                $action = 'action'.ucfirst(array_shift($segments));
            }
        }

        $controller = '\\'.ucfirst($namespace).'\\Controller\\'.$controller;

        if (\class_exists($controller)) {
            $controller = new $controller($response);
        }

        if (!\is_object($controller) or !\method_exists($controller, $action)) {
            throw new \Nerd\Http\Exception(404, $response);
        }

        if (!$controller instanceof \Nerd\Design\Architectural\MVC\Controller) {
            throw new \Exception('Corrupt application controller. Controller does not implement the MVC specification.');
        }

        $controller->before();

        if (($body = call_user_func_array(array($controller, $action), $segments)) != null) {
            $response->setBody($body);
        }

        $controller->after();
    }
}
