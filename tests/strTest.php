<?php

namespace Nerd;

class StrTest extends \PHPUnit_Framework_TestCase
{
	protected $ref;

	public function setUp()
	{
		$this->ref = new \ReflectionClass('\\Nerd\\Str');
	}

	/**
	 * @covers \Nerd\Str
	 */
	public function testStrIsUninstantiable()
	{
		$this->assertFalse($this->ref->hasMethod('__construct'));
	}

	/**
	 * @covers \Nerd\Str::lower
	 */
	public function testStrLower()
	{
		$this->assertEquals('name', Str::lower('NaMe'));
	}

	/**
	 * @covers \Nerd\Str::upper
	 */
	public function testStrUpper()
	{
		$this->assertEquals('NAME', Str::upper('NaMe'));
	}

	/**
	 * @covers \Nerd\Str::length
	 */
	public function testStrLength()
	{
		$this->assertEquals(4, Str::length('NaMe'));
	}

	/**
	 * @covers \Nerd\Str::sub
	 */
	public function testStrSub()
	{
		$this->assertEquals('na', Str::sub('name', 0, 2));
	}

	/**
	 * @covers \Nerd\Str
	 */
	public function testStrMbstringSet()
	{
		$this->assertEquals(function_exists('mb_get_info'), Str::$mbString);
	}

	/**
	 * @covers \Nerd\Str::__callStatic
	 */
	public function testStrCanBeCalledStatically()
	{
		$this->assertTrue($this->ref->hasMethod('__callStatic'));
		$this->assertEquals(Str::__callStatic('lower', ['NAME']), 'name');
	}

	/**
	 * @covers \Nerd\Str::is
	 */
	public function testStrIsSucceeds()
	{
		$this->assertTrue(Str::is('string'));
	}

	/**
	 * @covers \Nerd\Str::is
	 */
	public function testStrIsMultipleSucceeds()
	{
		$this->assertTrue(Str::is('string', 'second string'));
	}

	/**
	 * @covers \Nerd\Str::is
	 */
	public function testStrIsFails()
	{
		$this->assertFalse(str::is(123456));
		$this->assertFalse(Str::is(new \stdClass()));
	}

	/**
	 * @covers \Nerd\Str::is
	 */
	public function testStrIsMultipleFails()
	{
		$this->assertFalse(Str::is('string', 123456));
		$this->assertFalse(Str::is(new \stdClass(), 65489));
	}

	/**
	 * @covers \Nerd\Str::is
	 */
	public function testStrIsReturnsBoolean()
	{
		$this->assertTrue(is_bool(Str::is('string')));
		$this->assertTrue(is_bool(Str::is(['1', 2])));
	}

	/**
	 * @covers \Nerd\Str::abbreviate
	 */
	public function testStrAbbreviate()
	{
		$this->assertEquals('sttn', Str::abbreviate('station'));
	}

	/**
	 * @covers \Nerd\Str::title
	 */
	public function testStrTitle()
	{
		$this->assertEquals(Str::title('my string title'), 'My String Title');
	}

	/**
	 * @covers \Nerd\Str::humanize
	 */
	public function testStrHumanize()
	{
		$this->assertEquals(Str::humanize('my_human_string'), 'My Human String');
	}

	/**
	 * @covers \Nerd\Str::random
	 */
	public function testStrRandomCorrectLength()
	{
		$length = 16;
		$random = Str::random($length);

		$this->assertEquals($length, strlen($random));
	}

	/**
	 * @covers \Nerd\Str::pool
	 */
	public function testStrPool()
	{
		// Need to write some regex's to match the output.
		$this->assertTrue(true);
	}

	/**
	 * @covers \Nerd\Str::__initialize
	 */
	public function testStrHasInitializer()
	{
		$this->assertTrue($this->ref->hasMethod('__initialize'));
		$this->assertTrue($this->ref->implementsInterface('\\Nerd\\Design\\Initializable'));
	}
}
