<?php

use \Nerd\Arr;

class ArrTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider data
	 */
	public function testArrGet($users)
	{
		$actual = Arr::get($users, 'Frank.id');
		$this->assertEquals($actual, 123);
	}

	/**
	 * @dataProvider data
	 */
	public function testArrGetDefault($users)
	{
		$this->assertNull(Arr::get($users, 'nonexistent.key'));

		$actual  = Arr::get($users, 'nonexistent.key', 'default');
		$this->assertEquals($actual, 'default');
	}

	/**
	 * @dataProvider data
	 */
	public function testArrDelete()
	{

	}

	public function testCanTellIfIsArray()
	{
		$this->assertTrue(Arr::is( [1] ));
		$this->assertTrue(Arr::is( [1], [2] ));
	}

	public function testCanTellIfNotArray()
	{
		$this->assertFalse(Arr::is('string'));
		$this->assertFalse(Arr::is(123));
		$this->assertFalse(Arr::is(false));

		// Should fail for objects
		$obj = new \StdClass();
		$this->assertFalse(Arr::is($obj));
	}

	public function testCanTellIfOneArgNotArray()
	{
		$this->assertFalse(Arr::is([1], 'string'));
	}

	public function data()
	{
		return [[[

			'Frank' => [
				'id' => 123,
				'email' => 'frank@test.com'
			],

			'Antoine' => [
				'id' => 456,
				'email' => 'antoine@test.com'
			],
		]]];
	}
}
