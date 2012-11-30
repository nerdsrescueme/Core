<?php

/**
 * Core Nerd library namespace. This namespace contains all the fundamental
 * components of Nerd, plus additional utilities that are provided by default.
 * Some of these default components have sub namespaces if they provide child
 * objects.
 *
 * @package    Nerd
 * @subpackage Core
 */
namespace Nerd;

/**
 * Crypt(ography) class
 *
 * This class provides powerful cryptographic functionality enabling keyed
 * encryption using drivers like XOR and Mcrypt. Additionally, this class
 * implements secure hashing techniques to aid in the uniqueness of your
 * applications hashed data.
 *
 * @package    Nerd
 * @subpackage Core
 */
class Crypt extends Design\Creational\SingletonFactory
{
    /**
     * The encryption key
     *
     * @var    string
     */
    protected static $key;

    /**
     * Fetch the encryption key
     *
     * Returns the encrption key set in the application config, MD5'd in order
     * to ensure exact-length 128 bit keys. Mcrypt is sensitive to keys that are
     * not the correct length.
     *
     * ## Usage
     *
     *     $key = Crypt::key();
     *
     * @return string
     */
    public static function key()
    {
        if (static::$key === null) {
            static::$key = sha1(Config::get('application.securityKey'));
        }

        return static::$key;
    }

    /**
     * Hash a string using either sha1 or md5, with an extra layer of uniqueness
     * by attaching the crypt key.
     *
     * ## Usage
     *
     *     $hash = Crypt::hash($string);
     *
     * @param    string           The string to hash
     * @return string The hashed string
     */
    public static function hash($string)
    {
        return sha1($string.static::$key);
    }
}
