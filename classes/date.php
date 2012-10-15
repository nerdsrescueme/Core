<?php

/**
 * Core Nerd library namespace. This namespace contains all of the fundamental
 * components of the Nerd framework, plus additional utilities that are
 * provided by default. Some of these default components have sub namespaces
 * if they provide children objects.
 *
 * @package    Nerd
 * @subpackage Library
 */
namespace Nerd;

// Aliasing rules
use \Nerd\Date\Timezone;

/**
 * Date Class
 *
 * It is very important that you understand the PHP Date objects to understand
 * this classes implementation. It relies heavily on the object provided by PHP,
 * the following link would be very useful to read before you start using extend
 * or dive deep into this class.
 *
 * Inherited Class Constants:
 *
 * * Date::Nerd    = Y-m-d\TH:i:sP
 * * Date::COOKIE  = l, d-M-y H:i:s T
 * * Date::ISO8601 = Y-m-d\TH:i:sO
 * * Date::RFC822  = D, d M y H:i:s O
 * * Date::RFC850  = l, d-M-y H:i:s T
 * * Date::RFC1036 = D, d M y H:i:s O
 * * Date::RFC1123 = D, d M Y H:i:s O
 * * Date::RFC2822 = D, d M Y H:i:s O
 * * Date::RFC3339 = Y-m-d\TH:i:sP
 * * Date::RSS     = D, d M Y H:i:s O
 * * Date::W3C     = Y-m-d\TH:i:sP
 *
 * @see    http://us2.php.net/manual/en/class.datetime.php
 *
 * @package    Nerd
 * @subpackage Library
 */
class Date extends \DateTime
{
    // Traits
    use Design\Renderable;

    /**
     * Date properties
     *
     * Used by __get to translate property calls into their
     * corresponding date formats.
     *
     * @see    http://php.net/manual/en/function.date.php
     * @var    array
     */
    public static $properties = [

        'hours'         => 'g',
        'minutes'       => 'i',
        'seconds'       => 's',
        'meridien'      => 'a',
        'fromGMT'       => '0',
        'timezone'      => 'e',
        'timezoneAbbr'  => 'T',
        'day'           => 'l',
        'dayOfWeek'     => 'w',
        'dayOfMonth'    => 'j',
        'dayOfYear'     => 'z',
        'weekOfYear'    => 'W',
        'month'         => 'F',
        'year'          => 'Y',
        'isLeapYear'    => 'L',
        'timestamp'     => 'U',
    ];

    /**
     * Static class constructor
     *
     * @see    http://us2.php.net/manual/en/datetime.formats.php
     * @see    http://us2.php.net/manual/en/timezones.php
     *
     * @param    string                   Valid date string
     * @param    Nerd\Date\Timezone       Timezone object
     * @return Nerd\Date
     */
    public static function date($time = null, Timezone $timezone = null)
    {
        return new static($time, $timezone);
    }

    /**
     * Create a Date range based on an interval between a starting
     * and ending date.
     *
     * @see    http://us2.php.net/manual/en/datetime.formats.php
     *
     * @param    Nerd\Date           Starting date
     * @param    string              Interval between dates
     * @param    Nerd\Date           Ending date
     * @param    boolean             Exclude the start date?
     * @return Nerd\Date\Range Traversable date range
     */
    public static function range(Date $start, $interval, Date $end, $excludeStart = false)
    {
        return \Nerd\Date\Range::make($start, $interval, $end);
    }

    /**
     * Create a Date range based on a starting date, and repeating
     * a specified interval X number of times.
     *
     * @see    http://us2.php.net/manual/en/datetime.formats.php
     *
     * @param    Nerd\Date           Starting date
     * @param    string              Interval between dates
     * @param    integer             How many times to repeat the interval
     * @param    boolean             Exclude the start date?
     * @return Nerd\Date\Range Traversable date range
     */
    public static function interval(Date $start, $interval, $repeat, $excludeStart = false)
    {
        return \Nerd\Date\Range::make($start, $interval, (int) $repeat);
    }

    /**
     * When a property is accessed it is dynamically created, this
     * property holds a cached representation of that function call
     * so it never needs to be accessed more than once (unless the
     * date is modified).
     *
     * @var    array
     */
    private $propertyCache = [];

    /**
     * Class Constructor
     *
     * Create a new Date object and auto add the default timezone
     * if none is specified.
     *
     * @see    http://us2.php.net/manual/en/datetime.formats.php
     * @see    http://us2.php.net/manual/en/timezones.php
     * @see    http://php.net/manual/en/function.date.php
     *
     * @param    string       Valid date string
     * @param    string       Valid timezone
     * @param    string       Valid date string
     * @return Nerd\Date
     */
    public function __construct($time = null, Timezone $timezone = null, $format = null)
    {
        $timezone = ($timezone === null) ? \Nerd\Config::get('application.timezone', 'UTC') : $timezone;

        if (!$timezone instanceof \DateTimeZone) {
            $timezone = new Timezone($timezone);
        }

        if ($format === null) {
            return parent::__construct($time, $timezone);
        }

        return parent::createFromFormat($format, $time, $timezone);
    }

    /**
     * Return the current date in the specified format, you may use
     * values from the formats array in Nerd/config/date.php or a
     * date string.
     *
     * @see      http://php.net/manual/en/function.date.php
     *
     * @param    string     Config key or date string
     * @return string Formatted date
     */
    public function format($format = 'default')
    {
        return parent::format(\Nerd\Config::get("date.formats.$format", $format));
    }

    /**
     * Modify the current date.
     *
     * Note: Calling this method will clear the property cache.
     *
     * @param    string        String date modification
     * @return Nerd\Date Chainable.
     */
    public function modify($time)
    {
        $this->propertyCache = [];

        return parent::modify($time);
    }

    /**
     * Subtract date and time from current date.
     *
     * Note: Calling this method will clear the property cache.
     *
     * @param    string        String date modification
     * @return Nerd\Date Chainable.
     */
    public function sub($time)
    {
        $this->propertyCache = [];

        return parent::sub(\Nerd\Date\Interval::createFromDateString($time));
    }

    /**
     * Add date and time to current date.
     *
     * Note: Calling this method will clear the property cache.
     *
     * @param    string        String date modification
     * @return Nerd\Date Chainable.
     */
    public function add($time)
    {
        $this->propertyCache = [];

        return parent::add(\Nerd\Date\Interval::createFromDateString($time));
    }

    /**
     * Return the date in the default Config format
     *
     * @return string Default format or ISO8601
     */
    public function render()
    {
        return $this->format(Config::get('date.formats.default', 'Y-m-d H:i'));
    }

    /**
     * Magic Getter
     *
     * Provide access to the dates year, month, etc…
     *
     * @see    Nerd\Date::$properties
     */
    public function __get($property)
    {
        if (isset(static::$properties[$property])) {
            if (isset($this->propertyCache[$property])) {
                return $this->propertyCache[$property];
            }
            $this->propertyCache[$property] = $this->format(static::$properties[$property]);

            return $this->propertyCache[$property];
        }

        throw new \InvalidArgumentException('Call to undefined property Date::$'.$property);
    }
}
