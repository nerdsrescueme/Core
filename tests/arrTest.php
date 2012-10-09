<?php

use \Nerd\Arr;

class ArrTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::get
	 */
	public function testArrGet($users)
	{
		$actual = Arr::get($users, 'Frank.id');
		$this->assertEquals($actual, 123, 'Arr::get cannot retrieve values');
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::get
	 */
	public function testArrGetDefaultToNull($users)
	{
		$this->assertNull(Arr::get($users, 'nonexistent.key'), 'Arr::get does not default to null when no $default is provided');
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::get
	 */
	public function testArrGetDefaultWhenProvided($users)
	{
		$actual = Arr::get($users, 'nonexistent.key', 'default');
		$this->assertEquals($actual, 'default', 'Arr::get does not return $default value when no key is present');
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::get
	 * @depends testArrGetDefaultWhenProvided
	 */
	public function testArrGetDefaultClosureIsInvoked($users)
	{
		$closure = function() { return true; };
		$actual  = Arr::get($users, 'nonexistent.key', $closure);

		$this->assertTrue($actual);
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::delete
	 */
	public function testArrDelete($users)
	{
		$this->assertCount(2, $users);

		// Remove one key and test count again
		Arr::delete($users, 'Frank');
		$this->assertCount(1, $users, 'Arr::delete does not delete from referenced array');
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::set
	 */
	public function testArrSet($users)
	{
		$this->assertCount(2, $users);

		// Add a key and test count again
		Arr::set($users, 'NewUser', 'testdata');
		$this->assertCount(3, $users, 'Arr::set does not set a value on the referenced array');
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::has
	 */
	public function testArrHasSuccess($users)
	{
		$this->assertTrue(Arr::has($users, 'Frank'), 'Arr::has cannot determine if an array has a key');
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::has
	 */
	public function testArrHasFail($users)
	{
		$this->assertFalse(Arr::has($users, 'nonexistent'), 'Arr::has finds array keys that do not exist');
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsSuccess()
	{
		$this->assertTrue(Arr::is( [1] ), 'Arr::is is unable to properly determine if it had an array passed to it');
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsFail()
	{
		$this->assertFalse(Arr::is('string'), 'Arr::is believes that strings are arrays');
		$this->assertFalse(Arr::is(123), 'Arr::is believes that integers are arrays');
		$this->assertFalse(Arr::is(false), 'Arr::is believes that booleans are arrays');
		$this->assertFalse(Arr::is(new \StdClass()), 'Arr::is believes that objects are arrays');
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsMultipleSucceed()
	{
		$this->assertTrue(Arr::is( [1], [2] ), 'Arr::is is unable to determine if it had multiple arrays passed to it');
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsMultipleFail()
	{
		$this->assertFalse(Arr::is([1], 'string'), 'Arr::is does not fail if an array and another non-array is passed to it');
	}

	/**
   * @covers \Nerd\Arr::toEnumerable
   */
	public function testArrToEnum()
	{
		$enum = Arr::toEnumerable([1, 2, 3]);

		$this->assertTrue($enum instanceof \Nerd\Design\Enumerable, 'Arr::toEnumerable is unable to convert an array to an enumerable object');
	}

	/**
   * @covers \Nerd\Arr::toObject
   */
	public function testArrToObjectSucceed()
	{
		// Should succeed on an assoc array
		$obj = Arr::toObject(['test' => 'one']);
		$this->assertTrue(is_object($obj), 'Arr::toObject is unable to convert an array to an object');
	}

	/**
   * @covers \Nerd\Arr::toObject
   */
	public function testArrToObjectFail()
	{
		// Should fail on a normal array
		$obj = Arr::toObject([1,2,3]);
		$this->assertFalse(is_object($obj), 'Arr::toObject is able to convert a non-associative array to an object, it shouldnt');
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
