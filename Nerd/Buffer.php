<?php

namespace Nerd;

class Buffer
{
	/**
	 * If output capturing is currently active
	 *
	 * @var boolean
	 */
	private static $capturing = false;

	/**
	 * If output buffering has been started
	 *
	 * @var integer
	 */
	private static $started = false;


	/**
	 * Erases the output buffer
	 *
	 * @return void
	 */
	static public function erase()
	{
		if (!self::$started) {
			throw new \RuntimeException('The output buffer can not be erased since output buffering has not been started');
		}
		if (self::$capturing) {
			throw new \RuntimeException('Output capturing is currently active and it must be stopped before the buffer can be erased');
		}
		ob_clean();
	}


	/**
	 * Returns the contents of output buffer
	 *
	 * @return string  The contents of the output buffer
	 */
	static public function get()
	{
		if (!self::$started) {
			throw new \RuntimeException('The output buffer can not be erased since output buffering has not been started');
		}
		if (self::$capturing) {
			throw new \RuntimeException('Output capturing is currently active and it must be stopped before the buffer can be erased');
		}
		return ob_get_contents();
	}


	/**
	 * Checks if buffering has been started
	 *
	 * @return boolean  If buffering has been started
	 */
	static public function isStarted()
	{
		return self::$started;
	}


	/**
	 * Replaces a value in the output buffer
	 *
	 * @param  string $find     The string to find
	 * @param  string $replace  The string to replace
	 * @return void
	 */
	static public function replace($find, $replace)
	{
		if (!self::$started) {
			throw new \RuntimeException('The output buffer can not be erased since output buffering has not been started');
		}
		if (self::$capturing) {
			throw new \RuntimeException('Output capturing is currently active and it must be stopped before the buffer can be erased');
		}

		// ob_get_clean() actually turns off output buffering, so we do it the long way
		$contents = ob_get_contents();
		ob_clean();

		echo str_replace($find, $replace, $contents);
	}


	/**
	 * Resets the configuration and buffer of the class
	 *
	 * @internal
	 *
	 * @return void
	 */
	static public function reset()
	{
		if (self::$capturing) {
			self::stopCapture();
		}
		if (self::$started) {
			self::erase();
			self::stop();
		}
	}


	/**
	 * Starts output buffering
	 *
	 * @param  boolean $gzip  If the buffered output should be gzipped using [http://php.net/ob_gzhandler `ob_gzhandler()`]
	 * @return void
	 */
	static public function start($gzip = false)
	{
		if (!self::$started) {
			throw new \RuntimeException('The output buffer can not be erased since output buffering has not been started');
		}
		if (self::$capturing) {
			throw new \RuntimeException('Output capturing is currently active and it must be stopped before the buffer can be erased');
		}
		if ($gzip && !extension_loaded('zlib')) {
			throw new \RuntimeException('The PHP zlib extension is required for gzipped buffering, however is does not appear to be loaded');
		}
		ob_start($gzip ? 'ob_gzhandler' : null);
		self::$started = true;
	}


	/**
	 * Starts capturing output, should be used with ::stopCapture() to grab output from code that does not offer an option of returning a value instead of outputting it
	 *
	 * @return void
	 */
	static public function startCapture()
	{
		if (!self::$started) {
			throw new \RuntimeException('The output buffer can not be erased since output buffering has not been started');
		}
		ob_start();
		self::$capturing = true;
	}


	/**
	 * Stops output buffering, flushing everything to the browser
	 *
	 * @return void
	 */
	static public function stop()
	{
		if (!self::$started) {
			throw new \RuntimeException('The output buffer can not be erased since output buffering has not been started');
		}
		if (self::$capturing) {
			throw new \RuntimeException('Output capturing is currently active and it must be stopped before the buffer can be erased');
		}

		// Only flush if there is content to push out, otherwise
		// we might prevent headers from being sent
		if (ob_get_contents()) {
			ob_end_flush();
		} else {
			ob_end_clean();
		}

		self::$started = false;
	}


	/**
	 * Stops capturing output, returning what was captured
	 *
	 * @return string  The captured output
	 */
	static public function stopCapture()
	{
		if (!self::$capturing) {
			throw new RuntimeException('Output capturing can not be stopped since it has not been started');
		}
		self::$capturing = false;
		return ob_get_clean();
	}


	/**
	 * Forces use as a static class
	 *
	 * @return fBuffer
	 */
	private function __construct() { }
}
