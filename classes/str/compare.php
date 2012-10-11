<?php

/**
 * Str helper namespace. This namespace provides additional helper fucntionality
 * to strings that are not as commonly used. This separation happens to prevent
 * functions from existing in memory when they're not in active use.
 *
 * @package Nerd
 * @subpackage Str
 */
namespace Nerd\Str;

/**
 * String Comparison Class
 *
 * Performs complex string matching tasks generally used in lexigraphical and
 * database operations. Many times you will find yourself wondering how bigger
 * companies are able to offer suggestions on searches, or even read mispelled
 * words correctly. This class helps with that.
 *
 * @package Nerd
 * @subpackage Str
 */
class Compare extends \Nerd\Str
{

	/**
	 * Maximum length of comparison strings
	 *
	 * If you notice these comparisions taking a long time to run, simply reduce this
	 * value to a lower amount and the string will be truncated to the legnth defined
	 *
	 * @var    int
	 */
	public static $stringLimit = 255;

	/**
	 * Precision of percentage values returned from class methods
	 *
	 * @var    int
	 */
	public static $precision = 4;


	/**
	 * Calculate the levenstein distance between two strings.
	 *
	 * This method will first truncate the given strings to a length more suitable to
	 * the levenstein function. This limits memory usage and the time it takes to perform
	 * the function. You can optionally do a case sensitive distance which will then
	 * distinguish between upper and lowercase characters.
	 *
	 * @param    string    String to compare
	 * @param    string    String to compare against
	 * @param    boolean   Case sensitive comparison?
	 * @return   integer   Levenstein distance between two strings
	 */
	public static function levenshtein($comparer, $comparee, $caseSensitive = false)
	{
		if (!$caseSensitive)
		{
			$comparer = static::lower($comparer);
			$comparee = static::lower($comparee);
		}
		
		$comparer = static::sub($comparer, 0, static::$stringLimit);
		$comparee = static::sub($comparee, 0, static::$stringLimit);
		
		return (int) levenshtein($comparer, $comparee);
	}

	/**
	 * Calculate the difference between two metaphone strings
	 *
	 * This method converts two strings into their corresponding metaphone key values.
	 * It then calculates the levenstein distance between those two keys and returns
	 * a percentage difference between the two.
	 *
	 * @param    string     String to compare
	 * @param    string     String to compare against
	 * @return   integer    Percentage difference between the two strings
	 */
	public static function metaphone($comparer, $comparee)
	{
		$comparer = metaphone(static::sub($comparer), static::$stringLimit);
		$comparee = metaphone(static::sub($comparee), static::$stringLimit);
		$ls       = static::levenshtein($comparer, $comparee);

		return (int) number_format(($ls/static::length($comparee)*100), static::$precision);
	}

	/**
	 * Find how many characters match between two strings
	 *
	 * Essentially, this is a wrapper around php's similar_text function which finds how
	 * many characters match in a given string. It simplifies the coding by allowing you
	 * to easily return this value as a percentage instead of an actual character count.
	 *
	 * @param    string     String to compare
	 * @param    string     String to compare against
	 * @param    boolean    Return result as a percentage?
	 * @return   integer    Character match count or a percentage of that value
	 */
	public static function similar($comparer, $comparee, $asPercentage = false)
	{
		if ($asPercentage)
		{
			$percentage = 0;
			similar_text($comparer, $comparee, $percentage);
			return $percentage;
		}

		return similar_text($comparer, $comparee);
	}

	/**
	 * Calculate the difference between two soundex strings
	 *
	 * This method converts two strings into their corresponding soundex key values.
	 * It then calculates the levenstein distance between those two keys and returns
	 * a percentage difference between the two.
	 *
	 * @param    string     String to compare
	 * @param    string     String to compare against
	 * @return   integer    Percentage difference between the two strings
	 */
	public static function soundex($comparer, $comparee)
	{
		$comparer = soundex(static::sub($comparer), static::$stringLimit);
		$comparee = soundex(static::sub($comparee), static::$stringLimit);
		$ls       = static::levenshtein($comparer, $comparee);
		
		return (float) number_format(($ls/static::length($comparee)*100), static::$precision);
	}
}