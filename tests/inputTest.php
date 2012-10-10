<?php

namespace Nerd;

class InputTest extends \PHPUnit_Framework_TestCase
{
	// Currently not testing Input::put... not sure how I'm going to accomplish
	// that just yet.

	protected $ref;

	public function setUp()
	{
		$this->ref = new \ReflectionClass('\\Nerd\\Input');

		$data = [
			'key' => 'value',
			'layer' => ['key' => 'value'],
			'MINE' => 'value',
			'REMOTE_ADDR' => '123.456.789.123',
		];

		$_COOKIE = $data;
		$_POST   = $data;
		$_GET    = $data['layer'];
		$_SERVER = $data;
		$_FILES  = $data;
		$_ENV    = $data;
	}

	/**
	 * @covers \Nerd\Input::cookie
	 */
	public function testInputCookie()
	{
		$actual = Input::cookie('key');
		$this->assertEquals($actual, 'value', 'Input::cookie cannot retrieve values');
	}

	/**
	 * @covers \Nerd\Input::cookie
	 */
	public function testInputCookieDefaultToNull()
	{
		$result = Input::cookie('nonexistent.key');
		$this->assertNull($result, 'Input::cookie does not default to null when no $default is provided');
	}

	/**
	 * @covers \Nerd\Input::cookie
	 */
	public function testInputCookieDefaultWhenProvided()
	{
		$actual = Input::cookie('nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Input::cookie does not return $default value when no key is present');
	}

	/**
	 * @covers \Nerd\Input::cookie
	 * @depends testInputCookieDefaultWhenProvided
	 */
	public function testInputCookieDefaultClosureIsInvoked()
	{
		$closure = function() { return true; };
		$actual  = Input::cookie('nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @covers \Nerd\Input::env
	 */
	public function testInputEnv()
	{
		$actual = Input::env('key');
		$this->assertEquals($actual, 'value', 'Input::env cannot retrieve values');
	}

	/**
	 * @covers \Nerd\Input::env
	 */
	public function testInputEnvDefaultToNull()
	{
		$result = Input::env('nonexistent.key');
		$this->assertNull($result, 'Input::env does not default to null when no $default is provided');
	}

	/**
	 * @covers \Nerd\Input::cookie
	 */
	public function testInputEnvDefaultWhenProvided()
	{
		$actual = Input::env('nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Input::env does not return $default value when no key is present');
	}

	/**
	 * @covers \Nerd\Input::env
	 * @depends testInputEnvDefaultWhenProvided
	 */
	public function testInputEnvDefaultClosureIsInvoked()
	{
		$closure = function() { return true; };
		$actual  = Input::env('nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @covers \Nerd\Input::file
	 */
	public function testInputFile()
	{
		$actual = Input::file('key');
		$this->assertEquals($actual, 'value', 'Input::file cannot retrieve values');
	}

	/**
	 * @covers \Nerd\Input::file
	 */
	public function testInputFileDefaultToNull()
	{
		$result = Input::file('nonexistent.key');
		$this->assertNull($result, 'Input::file does not default to null when no $default is provided');
	}

	/**
	 * @covers \Nerd\Input::file
	 */
	public function testInputFileDefaultWhenProvided()
	{
		$actual = Input::file('nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Input::file does not return $default value when no key is present');
	}

	/**
	 * @covers \Nerd\Input::File
	 * @depends testInputFileDefaultWhenProvided
	 */
	public function testInputFileDefaultClosureIsInvoked()
	{
		$closure = function() { return true; };
		$actual  = Input::file('nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @covers \Nerd\Input::get
	 */
	public function testInputGet()
	{
		$actual = Input::get('key');
		$this->assertEquals($actual, 'value', 'Input::get cannot retrieve values');
	}

	/**
	 * @covers \Nerd\Input::get
	 */
	public function testInputGetDefaultToNull()
	{
		$result = Input::get('nonexistent.key');
		$this->assertNull($result, 'Input::get does not default to null when no $default is provided');
	}

	/**
	 * @covers \Nerd\Input::get
	 */
	public function testInputGetDefaultWhenProvided()
	{
		$actual = Input::get('nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Input::get does not return $default value when no key is present');
	}

	/**
	 * @covers \Nerd\Input::get
	 * @depends testInputGetDefaultWhenProvided
	 */
	public function testInputGetDefaultClosureIsInvoked()
	{
		$closure = function() { return true; };
		$actual  = Input::get('nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @covers \Nerd\Input::ip
	 */
	public function testInputIp()
	{
		$actual = Input::ip();
		$this->assertEquals($actual, '123.456.789.123', 'Input::ip can not retrieve IP from the $_SERVER array');
	}

	/**
	 * @covers \Nerd\Input::post
	 */
	public function testInputPost()
	{
		$actual = Input::post('key');
		$this->assertEquals($actual, 'value', 'Input::post cannot retrieve values');
	}

	/**
	 * @covers \Nerd\Input::post
	 */
	public function testInputPostDefaultToNull()
	{
		$result = Input::post('nonexistent.key');
		$this->assertNull($result, 'Input::post does not default to null when no $default is provided');
	}

	/**
	 * @covers \Nerd\Input::post
	 */
	public function testInputPostDefaultWhenProvided()
	{
		$actual = Input::post('nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Input::post does not return $default value when no key is present');
	}

	/**
	 * @covers \Nerd\Input::post
	 * @depends testInputPostDefaultWhenProvided
	 */
	public function testInputPostDefaultClosureIsInvoked()
	{
		$closure = function() { return true; };
		$actual  = Input::post('nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @covers \Nerd\Input::server
	 */
	public function testInputServer()
	{
		$actual = Input::server('mine');
		$this->assertEquals($actual, 'value', 'Input::server cannot retrieve values');
	}

	/**
	 * @covers \Nerd\Input::server
	 */
	public function testInputServerDefaultToNull()
	{
		$result = Input::server('nonexistent.key');
		$this->assertNull($result, 'Input::server does not default to null when no $default is provided');
	}

	/**
	 * @covers \Nerd\Input::server
	 */
	public function testInputServerDefaultWhenProvided()
	{
		$actual = Input::server('nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Input::server does not return $default value when no key is present');
	}

	/**
	 * @covers \Nerd\Input::server
	 * @depends testInputServerDefaultWhenProvided
	 */
	public function testInputServerDefaultClosureIsInvoked()
	{
		$closure = function() { return true; };
		$actual  = Input::server('nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @covers \Nerd\Input
	 */
	public function testInputIsUninstantiable()
	{
		$this->assertFalse($this->ref->hasMethod('__construct'));
	}

	/**
	 * @covers \Nerd\Input::__initialize
	 */
	public function testInputHasInitializer()
	{
		$this->assertTrue($this->ref->hasMethod('__initialize'));
		$this->assertTrue($this->ref->implementsInterface('\\Nerd\\Design\\Initializable'));
	}
}
