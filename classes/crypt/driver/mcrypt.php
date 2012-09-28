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
use Nerd\Config;
use Nerd\Crypt;
use Nerd\Str;

/**
 * MCrypt driver for Crypt
 *
 * This driver enables access to the Mcrypt PHP extension for encryption and
 * decryption.
 *
 * @package    Nerd
 * @subpackage Crypt.Drivers
 */
class Mcrypt implements \Nerd\Crypt\Driver, \Nerd\Design\Initializable
{
	use \Nerd\Design\Creational\Singleton;

	/**
	 * Magic method called when creating a new instance of the object from the
	 * autoloader.
	 *
	 * @return   void             No value is returned
	 */
	public static function __initialize()
	{
		if(!\function_exists('mcrypt_encrypt'))
		{
			throw new \Exception('The mcrypt php extension is required to use the Mcrypt driver');
		}

		static::$cipher = Config::get('crypt.mcrypt.cipher');
		static::$mode   = Config::get('crypt.mcrypt.mode');
	}
	/**
	 * The encryption cipher.
	 *
	 * @var    string
	 */
	private static $cipher;

	/**
	 * The encryption mode
	 *
	 * @var    string
	 */
	private static $mode;

	/**
	 * Encrypt a value
	 *
	 * ## Usage
	 *
	 *     $driver->encrypt($string);
	 *
	 * @param    string           The value to encrypt
	 * @return   string           The encrypted value
	 */
	public function encrypt($string)
	{
		$iv = \mcrypt_create_iv($this->iv_size(), $this->randomizer());
		return \base64_encode($iv.\mcrypt_encrypt(static::$cipher, Crypt::key(), $string, static::$mode, $iv));
	}

	/**
	 * Decrypt a value
	 *
	 * ## Usage
	 *
	 *     $driver->decrypt($string);
	 *
	 * @param    string           The encrypted value
	 * @return   string           The decrypted value
	 */
	public function decrypt($string)
	{
		if(!Str::is($string = \base64_decode($string, true)))
		{
			throw new \Exception('Decryption error. Input value is not valid base64 data.');
		}

		list($iv, $string) = array(Str::sub($string, 0, $this->iv_size()), Str::sub($string, $this->iv_size()));

		return \rtrim(\mcrypt_decrypt(static::$cipher, Crypt::key(), $string, static::$mode, $iv), "\0");
	}

	/**
	 * Get the random number source available to the OS.
	 *
	 * @return   integer
	 */
	protected function randomizer()
	{
		if(\defined('MCRYPT_DEV_URANDOM'))
		{
			return \MCRYPT_DEV_URANDOM;
		}
		elseif(\defined('MCRYPT_DEV_RANDOM'))
		{
			return \MCRYPT_DEV_RANDOM;
		}

		return \MCRYPT_RAND;
	}

	/**
	 * Get the input vector size for the cipher and mode.
	 *
	 * Different ciphers and modes use varying lengths of input vectors.
	 *
	 * @return   integer
	 */
	private function iv_size()
	{
		return \mcrypt_get_iv_size(static::$cipher, static::$mode);
	}
}