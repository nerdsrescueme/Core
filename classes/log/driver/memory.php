<?php

/**
 * Log driver namespace. This namespace controls the driver specification for log
 * drivers. Multiple drivers for logging can be loaded at the same time, all of
 * which are singleton references to ensure no additional overhead.
 *
 * @package Nerd
 * @subpackage Log
 */
namespace Nerd\Log\Driver;

/**
 * Memory log driver class
 *
 * @package Nerd
 * @subpackage Log
 */
class Memory extends \Nerd\Log\Driver
{
    // Traits
    use \Nerd\Design\Creational\Singleton;

    /**
     * In memory log
     *
     * @var array
     */
    protected $log = [];

    /**
     * {@inheritdoc}
     */
    public function write($level, $message)
    {
        $level   = $this->parseLevel($level);
        $message = $this->stringify($message);

        return array_unshift($this->log, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function get($rows = null)
    {
        if ($rows === null) {
            return $this->log;
        }

        return array_slice($this->log, 0, $rows);
    }
}
