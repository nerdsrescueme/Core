<?php

/**
 * Format driver namespace. This namespace controls the format driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Format
 */
namespace Nerd\Format\Driver;

/**
 * XML format driver class
 *
 * The driver handles the formatting of xml data
 *
 * @package    Nerd
 * @subpackage Format
 */
class Xml implements \Nerd\Format\Driver
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * Converts a raw data value into its array equivelant
	 *
	 * @param    string           The raw data to convert
	 * @param    integer          Flags relavent to the formatting
	 * @return   array|boolean    The array equivelant of the raw data, otherwise false on failure
	 * @throws   \InvalidArgumentException   Throws an InvalidArgumentException if $data is not a string, or the correct format of data
	 */
	public function from($data, $flags = null)
	{
		return json_decode(json_encode(simplexml_load_string($data)), true);
	}

	/**
	 * Converts an array into its raw data value equivelant
	 *
	 * @param    array           The array to convert
	 * @param    integer         Flags relavent to the formatting
	 * @return   string|boolean  The raw data equivelant of the array, otherwise false on failure
	 * @throws   \InvalidArgumentException   Throws an InvalidArgumentException if $data is not an array
	 */
	public function to($data, $flags = null)
	{
		!is_array($data) and $data = (array) $data;
 
		$to_xml = function(array $data, \SimpleXMLElement $xml = null) use (&$to_xml)
		{
			$xml === null and $xml = new \SimpleXMLElement('<root />');

			foreach($data as $key => $value)
			{
				// No numeric keys
				is_numeric($key) and $key = 'item';

				// Recurse for objects and arrays
				if(is_array($value) or is_object($value))
				{
					$to_xml((array) $value, $xml->addChild($key));
				}
				else
				{
					$xml->addChild($key, $value);
				}				
			}

			return $xml;
		};

		if($key = key($data) and !is_numeric($key))
		{
			return $to_xml($data[$key], new \SimpleXMLElement('<'.$key.' />'))->asXML();
		}

		return $to_xml($data)->asXML();
	}
}

/* End of file xml.php */