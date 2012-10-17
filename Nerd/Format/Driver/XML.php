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
 * @package    Nerd
 * @subpackage Format
 */
class XML implements \Nerd\Format\Driver
{
    use \Nerd\Design\Creational\Singleton;

    /**
     * {@inheritdoc}
     */
    public function from($data, $flags = null)
    {
        return json_decode(json_encode(simplexml_load_string($data)), true);
    }

    /**
     * {@inheritdoc}
     */
    public function to($data, $flags = null)
    {
        // If the data is not an array, check to see if it's a Nerd serializable
        // object... otherwise force the data to be an array.
        if (!is_array($data)) {
            if (is_object($data) and $data instanceof \Nerd\Design\Serializable) {
                $data = $data->serialize();
            } else {
                $data = (array) $data;
            }
        }

        // Recursive function to create the XML object
        $to_xml = function(array $data, \SimpleXMLElement $xml = null) use (&$to_xml) {
            $xml === null and $xml = new \SimpleXMLElement('<root />');

            foreach ($data as $key => $value) {
                // No numeric keys
                is_numeric($key) and $key = 'item';

                // Recurse for objects and arrays
                if (is_array($value)) {
                    $to_xml($value, $xml->addChild($key));
                } elseif (is_object($value)) {
                    // If a subobject is a Nerd serializable object, honor it.
                    if ($value instanceof \Nerd\Design\Serializable) {
                        die('in there');
                        $to_xml($value->serialize(), $xml->addChild($key));
                    } else {
                        $to_xml($value, $xml->addChild($key));
                    }
                } else {
                    $xml->addChild($key, $value);
                }
            }

            return $xml;
        };

        return $to_xml($data)->asXML();
    }
}
