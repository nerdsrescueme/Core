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
 * Time Zone
 *
 * Holds an instance of a time zones. This enables easy calculations 
 * of dates and times, as well as simple changing of time zones.
 *
 * @see    http://us2.php.net/manual/en/class.datetimezone.php
 *
 * @package Nerd
 * @subpackage Date
 */
class Timezone extends \DateTimeZone
{
	/**
	 * Class Constants
	 *
	 * Used by static::list_identifiers to narrow down listings.
	 */
	const AFRICA = 1 ;
	const AMERICA = 2 ;
	const ANTARCTICA = 4 ;
	const ARCTIC = 8 ;
	const ASIA = 16 ;
	const ATLANTIC = 32 ;
	const AUSTRALIA = 64 ;
	const EUROPE = 128 ;
	const INDIAN = 256 ;
	const PACIFIC = 512 ;
	const UTC = 1024 ;
	const ALL = 2047 ;
	const ALL_WITH_BC = 4095 ;
	const PER_COUNTRY = 4096 ;
}