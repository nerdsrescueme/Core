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
 * Asset class
 *
 * The Asset class provides the ability to display and work with various types of
 * application assets including CSS, JS and images.
 *
 * ## Usage
 *
 * Assets may be loaded one at a time using the instance() method or by calling the
 * file type as a method.
 *
 *     Asset::instance('css', 'myfile.css');
 *     Asset::js('myfile.js');
 *
 * Assets may also be loaded as a collection object which will be able to be
 * manipulated in many different ways including built in compression functions.
 *
 *     $assets = Asset::collection(array(
 *         'myfile.js',
 *         'second.js'
 *     ))
 *
 * @package    Nerd
 * @subpackage Core
 */
class Asset
{
    use Design\Creational\Factory
      , Design\Eventable;

    /**
     * Create an enumerable collection of assets.
     *
     * @param    array          Array of assets to load within the collection
     * @return \Nerd\Asset\Collection
     */
    public static function collection(array $assets = [], $folder = null)
    {
        $collection = new Asset\Collection();

        if ($folder !== null) {
            $collection->folder = $folder;
        }

        foreach ($assets as $key => $asset) {
            if (!$asset instanceof Nerd\Asset\Driver) {
                $assets[$key] = static::guess($asset, $collection->folder);
            }
        }

		$collection->add($assets);

        return $collection;
    }

    /**
     * Attempts to guess what type of asset is currently being loaded and returns an
     * instance of that Asset driver.
     *
     * @param    string    Path to Asset file
     * @return \Nerd\Asset\Type
     */
    public static function guess($file, $folder = '')
    {
        $file = str_replace([DS, '/'], DS, $file);
        $file = explode('.', $file);

        return static::instance(end($file), implode('.', $file), $folder);
    }

    /**
     * The magic call static method is triggered when invoking inaccessible
     * methods in a static context.
     *
     * ## Usage
     *
     * This method exists to allow dynamic loading of different asset types.
     *
     *     Asset::css('mycssfile.css')
     *
     * @param    string           The method name being called
     * @param    array            The arguments being passed to the method call
     * @return mixed Returns the value of the intercepted method call
     */
    public static function __callStatic($method, array $params)
    {
        return forward_static_call_array('guess', $params);
    }
}
