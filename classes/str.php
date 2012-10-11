<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package  Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Str class
 *
 * The Str class provides helper functionality for working with php strings, as
 * well as providing encoding support. PHP needs to be compiled with the
 * --enable-mbstring option or a fallback without encoding support will be used
 * instead.
 *
 * @package Nerd
 * @subpackage Core
 */
class Str implements Design\Initializable
{
	/**
	 * Function map
	 *
	 * A large portion of the mbstring library functions identically to the
	 * native PHP string methods, except with Multibyte support. This map
	 * defines methods that are similar in nature, and allows them to be called
	 * via a the __callStatic method.
	 *
	 * @var    array
	 */
	protected static $functionMap = [

		'lower'  => array('mb_strtolower', 'strtolower'),
		'upper'  => array('mb_strtoupper', 'strtoupper'),
		'length' => array('mb_strlen', 'strlen'),
		'sub'    => array('mb_substr', 'substr'),
	];

	/**
	 * Whether the mbString extension is installed on this server
	 *
	 * @var    boolean
	 */
	public static $mbString = false;

	/**
	 * Magic method called when a class is first encountered by the Loader
	 * during file inclusion.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		static::$mbString = function_exists('mb_get_info');
	}

	/**
	 * The magic call static method is triggered when invoking inaccessible
	 * methods in a static context.
	 *
	 * ## Usage
	 *
	 * Any of the values that exist within the $functionMap can be utilized
	 * through this method. Please check each of the functions that exist in
	 * there, and check http://php.net for usage on each of the functions.
	 *
	 * @param    string           The method name being called
	 * @param    array            The arguments being passed to the method call
	 * @return   mixed            Returns the value of the intercepted method call
	 */
	public static function __callStatic($name, $arguments)
	{
		return call_user_func_array(static::$functionMap[$name][((static::$mbString === true) ? 0 : 1)], $arguments);
	}

	/**
	 * Determine whether a variable, or multiple variables, is of the string
	 * type. The only difference of this function compared to the native
	 * is_string function is that it allows you to pass multiple strings.
	 *
	 * ## Usage
	 *
	 *     $return = Str::is($string1, $string2, $string3);
	 *
	 * @param    mixed            Argument #n of variables to check
	 * @return   boolean          Returns true if all variables passed are strings, otherwise false
	 */
	public static function is()
	{
		$args = func_get_args();

		if(!count($args))
		{
			return false;
		}

		foreach($args as $var)
		{
			if(!is_string($var))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Abbreviate string by removing all vowels
	 *
	 * ## Usage
	 *
	 *     $abbr = Str::abbreviate('Signal'); // sgnl
	 *
	 * @param    string          The value to abbreviate
	 * @return   string          Abbreviated value
	 */
	public static function abbreviate($value)
	{
		return str_replace(['a','e','i','o','u','A','E','I','O','U'], '', $value);
	}

	/**
	 * Convert a string to title case (ucwords).
	 *
	 * ## Usage
	 *
	 *     $title = Str::title($foo);
	 *
	 * @param    string           The value to convert
	 * @return   string           The converted value
	 */
	public static function title($value)
	{
		return static::$mbString ? mb_convert_case($value, MB_CASE_TITLE) : ucwords(strtolower($value));
	}

	/**
	 * Convert a string to a human readable version
	 *
	 * ## Usage
	 *
	 *     $title = Str::humanize($foo);
	 *
	 * @param    string          The value to convert
	 * @return   string          The converted value
	 */
	public static function humanize($value)
	{
		return str_replace('_', ' ', static::title($value));
	}

	/**
	 * Generate a random alpha or alpha-numeric string.
	 *
	 * ## Usage
	 *
	 *     $random = Str::random(32, 'num'); // Returns a random, 32 character string consisting of only numbers
	 *
	 * @param    integer          The length of the random string
	 * @param    string           The character pool to use, from Str::pool
	 * @param    string           Prefix
	 * @return   string           Returns the generated random characters
	 */
	public static function random($length = 16, $type = 'alnum', $value = '')
	{
		$pool_length = static::length($pool = static::pool($type)) - 1;

		for($i = 0; $i < $length; $i++)
		{
			$value .= $pool[mt_rand(0, $pool_length)];
		}

		return $value;
	}

	/**
	 * Get a chracter pool.
	 *
	 * ## Usage
	 *
	 * The following character pools can be returned:
	 *
	 * * alnum : Alphanumeric characters
	 * * num : Numbers 0-9
	 * * nozero : Numbers 1-9
	 * * sym : Symbols
	 * * alnumsym : Alphanumeric characters and symbols
	 * * distinct : Numbers 2-9 and capital letters
	 * * hexdec : Hexadecimal letters and numbers (0-9, a-f)
	 * * alpha : a-Z and A-Z
	 *
	 *     $pool = Str::pool('nozero');
	 *
	 * @param    string           The type of pool to generate, defaults to 'alnum'
	 * @return   string           The pool characters
	 */
	public static function pool($type = 'alnum')
	{
		switch(static::lower($type))
		{
			case 'alnum':
				return '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case 'num':
				return '1234567890';
				break;
			case 'nozero':
				return '123456789';
				break;
			case 'sym':
				return '~`!@#$%^&*()_-+=/?><,.:;"\'[]{}|\\';
				break;
			case 'alnumsym':
				return '~`!@#$%^&*()_-+=/?><,.:;[]{}|\\0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
			case 'distinct':
				return '2345679ACDEFHJKLMNPRSTUVWXYZ';
				break;
			case 'hexdec':
				return '0123456789abcdef';
				break;
			case 'alpha':
			default:
				return 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
				break;
		}
	}
}