<?php

/**
 * Crypt driver namespace. This namespace controls the driver specification for
 * cryptographic drivers. Multiple drivers for cryptography can be loaded at
 * the same time, all of which are singleton references to ensure no additional
 * overhead.
 *
 * @package    Nerd
 * @subpackage Crypt
 */
namespace Nerd\Crypt;

/**
 * Crypt driver interface
 *
 * This interface defines the driver structure of which functions a driver must
 * implement and how they should be called.
 *
 * @package    Nerd
 * @subpackage Crypt
 */
interface Driver
{
    /**
     * Encrypt a value
     *
     * ## Usage
     *
     *     $driver->encrypt($string);
     *
     * @param    string           The value to encrypt
     * @return string The encrypted value
     */
    public function encrypt($string);

    /**
     * Decrypt a value
     *
     * ## Usage
     *
     *     $driver->decrypt($string);
     *
     * @param    string           The encrypted value
     * @return string The decrypted value
     */
    public function decrypt($string);
}
