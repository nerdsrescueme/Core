<?php

/**
 * Str helper namespace. This namespace provides additional helper fucntionality
 * to strings that are not as commonly used. This separation happens to prevent
 * functions from existing in memory when they're not in active use.
 *
 * @package    Nerd
 * @subpackage Str
 */
namespace Nerd\Str;

/**
 * Inflector class
 *
 * The Inflector class provides pluralization and singularization of English
 * nouns.
 *
 * @package    Nerd
 * @subpackage Str
 */
class Inflector
{
	/**
	 * The words that have been converted to singular.
	 *
	 * @var    array
	 */
	private static $singularCache = [];

	/**
	 * The words that have been converted to plural.
	 *
	 * @var    array
	 */
	private static $pluralCache = [];

	/**
	 * Plural word forms.
	 *
	 * @var    array
	 */
	private static $plural = [
		'/(quiz)$/i' => "$1zes",
		'/^(ox)$/i' => "$1en",
		'/([m|l])ouse$/i' => "$1ice",
		'/(matr|vert|ind)ix|ex$/i' => "$1ices",
		'/(x|ch|ss|sh)$/i' => "$1es",
		'/([^aeiouy]|qu)y$/i' => "$1ies",
		'/(hive)$/i' => "$1s",
		'/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
		'/(shea|lea|loa|thie)f$/i' => "$1ves",
		'/sis$/i' => "ses",
		'/([ti])um$/i' => "$1a",
		'/(tomat|potat|ech|her|vet)o$/i' => "$1oes",
		'/(bu)s$/i' => "$1ses",
		'/(alias)$/i' => "$1es",
		'/(octop)us$/i' => "$1i",
		'/(ax|test)is$/i' => "$1es",
		'/(us)$/i' => "$1es",
		'/s$/i' => "s",
		'/$/' => "s"
	];

	/**
	 * Singular word forms.
	 *
	 * @var    array
	 */
	private static $singular = [
		'/(quiz)zes$/i' => "$1",
		'/(matr)ices$/i' => "$1ix",
		'/(vert|ind)ices$/i' => "$1ex",
		'/^(ox)en$/i' => "$1",
		'/(alias)es$/i' => "$1",
		'/(octop|vir)i$/i' => "$1us",
		'/(cris|ax|test)es$/i' => "$1is",
		'/(shoe)s$/i' => "$1",
		'/(o)es$/i' => "$1",
		'/(bus)es$/i' => "$1",
		'/([m|l])ice$/i' => "$1ouse",
		'/(x|ch|ss|sh)es$/i' => "$1",
		'/(m)ovies$/i' => "$1ovie",
		'/(s)eries$/i' => "$1eries",
		'/([^aeiouy]|qu)ies$/i' => "$1y",
		'/([lr])ves$/i' => "$1f",
		'/(tive)s$/i' => "$1",
		'/(hive)s$/i' => "$1",
		'/(li|wi|kni)ves$/i' => "$1fe",
		'/(shea|loa|lea|thie)ves$/i' => "$1f",
		'/(^analy)ses$/i' => "$1sis",
		'/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
		'/([ti])a$/i' => "$1um",
		'/(n)ews$/i' => "$1ews",
		'/(h|bl)ouses$/i' => "$1ouse",
		'/(corpse)s$/i' => "$1",
		'/(us)es$/i' => "$1",
		'/(us|ss)$/i' => "$1",
		'/s$/i' => "",
	];

	/**
	 * Irregular word forms.
	 *
	 * @var    array
	 */
	private static $irregular = [
		'child' => 'children',
		'foot' => 'feet',
		'goose' => 'geese',
		'man' => 'men',
		'move' => 'moves',
		'person' => 'people',
		'sex' => 'sexes',
		'tooth' => 'teeth',
	];

	/**
	 * Uncountable word forms.
	 *
	 * @var    array
	 */
	private static $uncountable = [
		'equipment',
		'data',
		'deer',
		'fish',
		'information',
		'money',
		'rice',
		'series',
		'sheep',
		'species',
	];

	/**
	 * Get the plural form of a word if the specified count is greater than one.
	 *
	 * ## Usage
	 *
	 *     $count = 22;
	 *     echo Str\Inflector::pluralIf('word', $count);
	 *
	 * @param    string           The value to pluralize
	 * @param    integer          The specified value to validate against
	 * @return   string           Returns either the specified value, or the pluralized value if $count is greater than one
	 */
	public static function pluralIf($value, $count)
	{
		return ($count > 1) ? static::plural($value) : $value;
	}

	/**
	 * Convert a word to its plural form.
	 *
	 * ## Usage
	 *
	 *     echo Str\Inflector::plural('word');
	 *
	 * @param    string           The value to pluralize
	 * @return   string           The pluralized value
	 */
	public static function plural($value)
	{
		$irregular = array_flip(static::$irregular);
		$plural = static::inflect($value, static::$pluralCache, $irregular, static::$plural);

		return static::$pluralCache[$value] = $plural;
	}

	/**
	 * Get the singular form of a word if the specified count is greater than one.
	 *
	 * ## Usage
	 *
	 *     $count = 22;
	 *     echo Str\Inflector::singularIf('words', $count);
	 *
	 * @param    string           The value to singularize
	 * @param    integer          The specified value to validate against
	 * @return   string           Returns either the specified value, or the singularized value if $count is greater than one
	 */
	public static function singularIf($value, $count)
	{
		return ($count > 1) ? static::singular($value) : $value;
	}

	/**
	 * Convert a word to its singular form.
	 *
	 * ## Usage
	 *
	 *     echo Str\Inflector::singular('words');
	 *
	 * @param    string           The value to singularize
	 * @return   string           The singularized value
	 */
	public static function singular($value)
	{
		$singular = static::inflect($value, static::$singularCache, static::$irregular, static::$singular);

		return static::$singularCache[$value] = $singular;
	}

	/**
	 * Convert a word to its singular or plural form.
	 *
	 * @param    string           The value to inflect
	 * @param    array            A cache of inflection values
	 * @param    array            A cache or irregular values
	 * @param    array            The source to determine how to perform the inflection
	 * @return   string           Returns the inflected value
	 */
	private static function inflect($value, $cache, $irregular, $source)
	{
		if(isset($cache[$value]))
		{
			return $cache[$value];
		}

		if(in_array(strtolower($value), static::$uncountable))
		{
			return $value;
		}

		foreach($irregular as $irregular => $pattern)
		{
			if(preg_match($pattern = '/'.$pattern.'$/i', $value))
			{
				return preg_replace($pattern, $irregular, $value);
			}
		}

		foreach($source as $pattern => $inflected)
		{
			if(preg_match($pattern, $value))
			{
				return preg_replace($pattern, $inflected, $value);
			}
		}

		return $value;
	}
}