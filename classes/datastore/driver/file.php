<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore\Driver;

use Nerd\Datastore
  , Nerd\Config
  , Nerd\File as F
  , Nerd\Str;

/**
 * File datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class File implements \Nerd\Datastore\Driver, \Nerd\Design\Initializable
{
    use \Nerd\Design\Creational\Singleton;

    /**
     * The path to write to
     *
     * @var    string
     */
    public static $path = \Nerd\STORAGE_PATH;

    /**
     * Magic method called when a class is first encountered by the Autoloader,
     * providing static initialization.
     *
     * @return void No value is returned
     */
    public static function __initialize()
    {
        self::$path = join(DS, [\Nerd\STORAGE_PATH, 'datastore', Datastore::key()]);
    }

    /**
     * {@inheritdoc}
     */
    public function read($key)
    {
        if (!F::exists(self::$path.$key)) {
            return null;
        }

        // File based caches store have the expiration timestamp stored in
        // UNIX format prepended to their contents. This timestamp is then
        // extracted and removed when the cache is read to determine if the file
        // is still valid
        if (time() >= Str::sub($cache = F::get(self::$path.$key), 0, 10)) {
            $this->delete($key);

            return null;
        }

        return unserialize(substr($cache, 10));
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return F::exists(self::$path.$key);
    }

    /**
     * {@inheritdoc}
     */
    public function write($key, $value, $minutes = false)
    {
        !is_numeric($minutes) and $minutes = Config::get('datastore.time');
        $value = (time() + ($minutes * 60)).serialize($value);

        if (!F::exists(self::$path.$key)) {
            return F::create(self::$path.$key, $value);
        } else {
            return (bool) F::put(self::$path.$key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return F::delete(self::$path.$key);
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        rmdir(dirname(self::$path));
    }
}
