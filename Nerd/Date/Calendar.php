<?php

/**
* Core Date Namespace. This namespace contains all the functionality involving date
* creation and manipulation within Nerd.
*
* @package Nerd
* @subpackage Date
*/
namespace Nerd\Date;

// Aliasing rules
use \Nerd\Date;

/**
 * Calendar Class
 *
 * The calendar class provides convenience functionality wrapped around Nerd's
 * date class. Its purpose is to provide calendar objects that can be used to
 * easily generate calendar data.
 *
 * @see    Nerd\Date
 *
 * @package Nerd
 * @subpackage Date
 */
class Calendar
{
    /**
     * Class Constants
     */
    const TOMORROW    = '+1 day';
    const DAILY       = '+1 day';
    const HOURLY      = '+1 hour';
    const YESTERDAY   = '-1 day';
    const LAST_WEEK   = '-1 week';
    const NEXT_WEEK   = '+1 week';
    const LAST_MONTH  = '-1 month';
    const NEXT_MONTH  = '+1 month';
    const LAST_YEAR   = '-1 year';
    const NEXT_YEAR   = '+1 year';

    /**
     * Create a new calendar object
     *
     * The calendar object centers itself around the date object
     * provided upon instantiation. All subsequent method calls will
     * center around that date. For instance, if you set the day to
     * August 15, 2009, days_in_month will return an object containing
     * all the days in that month.
     *
     * @param    Nerd\Date|string     Date to center
     * @param    string               Timezone, use when day is string
     * @return Nerd\Calendar
     */
    public static function make($day = 'now', $timezone = null)
    {
        if (\is_object($day)) {
            return new static($day);
        }

        return new static(Date::make($day, $timezone));
    }

    /**
     * Centered Date
     *
     * @var    Nerd\Date
     */
    public $center;

    /**
     * Class Constructor
     *
     * @param    Nerd\Date        Centered date
     * @return Nerd\Calendar
     */
    public function __construct(\Nerd\Date $day)
    {
        $this->center = $day;

        return $this;
    }

    /**
     * Return a new calendar object with a centered day from
     * the previous month.
     *
     * @return Nerd\Calendar
     */
    public function getPreviousMonth()
    {
        return $this->getRelative(static::LAST_MONTH);
    }

    /**
     * Return a new calendar object with a centered day from
     * the next month.
     *
     * @return Nerd\Calendar
     */
    public function getNextMonth()
    {
        return $this->getRelative(static::NEXT_MONTH);
    }

    /**
     * Return a new calendar object with a centered day from
     * the previous week.
     *
     * @return Nerd\Calendar
     */
    public function getPreviousWeek()
    {
        return $this->getRelative(static::LAST_WEEK);
    }

    /**
     * Return a new calendar object with a centered day from
     * the next week.
     *
     * @return Nerd\Calendar
     */
    public function getNextWeek()
    {
        return $this->getRelative(static::NEXT_WEEK);
    }

    /**
     * Return a new calendar object with a centered date
     * relative to this centered date.
     *
     * @param    string     Relative date string
     * @return Nerd\Calendar
     */
    public function getRelative($relativeTime)
    {
        $calendar = clone $this;

        return $calendar->center->modify($relativeTime);
    }

    /**
     * Get a date range containing the days of the week from the
     * centered date.
     *
     * @param     string              Day that starts the week
     * @return Nerd\Date\Range
     */
    public function daysOfWeek($startDay = 'Sunday')
    {
        $currentDay = $this->center->format('F d Y');

        // Find the starting day, today or previous $start_day
        $first = ($startDay == $this->center->format('l'))
               ? $this->center
               : Date::make("previous $startDay $currentDay");

        $last = Date::make("next $startDay $currentDay")->modify('-1 day');

        return Date::range($first, static::DAILY, $last);
    }

    /**
     * Get a date range containing the days of the month based on
     * the centered date.
     *
     * @return Nerd\Date\Range
     */
    public function daysOfMonth()
    {
        $month = $this->center->format('F Y');
        $first = Date::make("first day of $month");
        $last  = Date::make("last day of $month");

        return Date::range($first, static::DAILY, $last);
    }

    /**
     * Magic Getter
     *
     * Forward properties to center date object.
     */
    public function __get($property)
    {
        return $this->center->$property;
    }
}
