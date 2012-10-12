<?php

namespace Nerd;

class Log extends Design\Creational\SingletonFactory implements Design\Initializable
{
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
	 * @var    string
	 */
	public static $defaultDriver = 'void';

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

	protected static $timezone;

	public static function __initialize()
	{
		static::$timezone = Config::get('application.timezone', 'UTC');
	}
}