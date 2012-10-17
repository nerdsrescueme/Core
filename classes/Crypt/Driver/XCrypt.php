<?php

/**
 * Crypt driver namespace. This namespace controls the crypt driver
 * implementations.
 *
 * @package    Nerd
 * @subpackage Crypt
 */
namespace Nerd\Crypt\Driver;

// Aliasing rules
use Nerd\Crypt;
use Nerd\Str;

/**
 * XOR encryption driver class
 *
 * this driver implements encoded bit-string using XOR, providing secure
 * encryption functionality on systems that either cannot, or choose not to
 * implement php extensions that provide more powerful methods.
 *
 * @package    Nerd
 * @subpackage Crypt.Drivers
 */
class Xcrypt implements \Nerd\Crypt\Driver
{
    use \Nerd\Design\Creational\Singleton;

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
    public function encrypt($string)
    {
        $rand = Crypt::hash(Str::random(32));
        $retval = '';

        for ($i = 0; $i < Str::length($string); $i++) {
            $retval .= Str::sub($rand, ($i % Str::length($rand)), 1).(Str::sub($rand, ($i % Str::length($rand)), 1) ^ Str::sub($string, $i, 1));
        }

        return \base64_encode($this->merge($retval, Crypt::key()));
    }

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
    public function decrypt($string)
    {
        if (!Str::is($string = \base64_decode($string, true))) {
            throw new \Exception('Decryption error. Input value is not valid base64 data.');
        }

        $string = $this->merge($string, Crypt::key());
        $retval = '';

        for ($i = 0; $i < Str::length($string); $i++) {
            $retval .= (Str::sub($string, $i++, 1) ^ Str::sub($string, $i, 1));
        }

        return $retval;
    }

    /**
     * XOR key + string Combiner
     *
     * Takes a string and key as input and computes the difference using XOR
     *
     * @param    string
     * @param    string
     * @return string
     */
    private function merge($string)
    {
        $hash = Crypt::key();
        $str  = '';

        for ($i = 0; $i < Str::length($string); $i++) {
            $str .= Str::sub($string, $i, 1) ^ Str::sub($hash, ($i % Str::length($hash)), 1);
        }

        return $str;
    }
}
