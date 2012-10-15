<?php

/**
 * Format driver namespace. This namespace controls the format driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Format
 */
namespace Nerd\Format\Driver;

/**
 * JSON format driver class
 *
 * @package    Nerd
 * @subpackage Format
 */
class Json implements \Nerd\Format\Driver
{
    use \Nerd\Design\Creational\Singleton;

    /**
     * {@inheritdoc}
     */
    public function from($data, $flags = null)
    {
        return json_decode($data, true);
    }

    /**
     * {@inheritdoc}
     */
    public function to($data, $flags = null)
    {
        return json_encode($data);
    }
}
