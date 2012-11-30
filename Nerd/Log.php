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
 * Log class
 *
 * The log class provides a convenient way to manage several loggers in your
 * application. Various log handlers are available to alert you to the status and
 * health of your application
 *
 * @package    Nerd
 * @subpackage Core
 */
class Log extends Design\Creational\SingletonFactory
{
    // Class constants
    const DEBUG     = 100;
    const INFO      = 200;
    const NOTICE    = 250;
    const WARNING   = 300;
    const ERROR     = 400;
    const CRITICAL  = 500;
    const ALERT     = 550;
    const EMERGENCY = 600;

    /**
     * The default driver to be utilized by your application in the event a
     * specific driver isn't called.
     *
     * @var string
     */
    public static $defaultDriver = 'void';

    /**
     * Integer to level array
     *
     * @var array
     */
    protected static $levels = [
        100 => 'DEBUG',
        200 => 'INFO',
        250 => 'NOTICE',
        300 => 'WARNING',
        400 => 'ERROR',
        500 => 'CRITICAL',
        550 => 'ALERT',
        600 => 'EMERGENCY',
    ];

    /**
     * Current timezone, used for dates
     *
     * @var string
     */
    protected static $timezone;


    public static function timezone()
    {
        if (static::$timezone === null) {
            static::$timezone = Config::get('application.timezone', 'UTC');
        }

        return static::$timezone;
    }
}
