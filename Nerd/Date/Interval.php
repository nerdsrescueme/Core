<?php

/**
* Core Date Namespace. This namespace contains all the functionality involving date
* creation and manipulation within Nerd.
*
* @package Nerd
* @subpackage Date
*/
namespace Nerd\Date;

/**
 * Date Interval
 *
 * An object containing a predefined interval between dates. It is
 * used in creating Date objects at defined intervals.
 *
 * @see    http://us2.php.net/manual/en/class.dateinterval.php
 *
 * @package Nerd
 * @subpackage Date
 */
class Interval extends \DateInterval
{
    /**
     * Class constants
     */
    const HOURLY        = '+1 hour';
    const DAILY         = '+1 day';
    const WEEKLY        = '+1 week';
    const BI_WEEKLY     = '+2 weeks';
    const MONTHLY       = '+1 month';
    const SEMI_ANNUALLY = '+6 months';
    const ANNUALLY      = '+1 year';

    public function getSpec()
    {
        return 'P'
        . (($this->y > 1) ? $this->y.'Y' : '')
        . (($this->m > 1) ? $this->m.'M' : '')
        . (($this->d > 1) ? $this->d.'D' : '')
        . (($this->h > 1) ? $this->h.'H' : '')
        . (($this->i > 1) ? $this->i.'M' : '')
        . (($this->s > 1) ? $this->s.'S' : '');
    }
}
