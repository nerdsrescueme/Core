<?php

/**
 * Datastore driver namespace. This controls the datastore driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Datastore
 */
namespace Nerd\Datastore\Driver;

/**
 * Void datastore driver class
 *
 * @package    Nerd
 * @subpackage Datastore
 */
class Void implements \Nerd\Datastore\Driver
{
    use \Nerd\Design\Creational\Singleton;

    /**
     * {@inheritdoc}
     */
    public function read($key)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function write($key, $value, $minutes = false)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        // Nothing needs to happen
    }
}
