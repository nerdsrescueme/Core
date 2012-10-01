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
		$this->assertEquals($actual, 123);
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::get
	 */
	public function testArrGetDefault($users)
	{
		$this->assertNull(Arr::get($users, 'nonexistent.key'));

		$actual = Arr::get($users, 'nonexistent.key', 'default');
		$this->assertEquals($actual, 'default');
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
		$this->assertCount(1, $users);
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
		$this->assertCount(3, $users);
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::has
	 */
	public function testArrHasSuccess($users)
	{
		$this->assertTrue(Arr::has($users, 'Frank'));
	}

	/**
	 * @dataProvider data
	 * @covers \Nerd\Arr::has
	 */
	public function testArrHasFail($users)
	{
		$this->assertFalse(Arr::has($users, 'nonexistent'));
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsSuccess()
	{
		$this->assertTrue(Arr::is( [1] ));
		$this->assertTrue(Arr::is( [1], [2] ));
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsFail()
	{
		$this->assertFalse(Arr::is('string'));
		$this->assertFalse(Arr::is(123));
		$this->assertFalse(Arr::is(false));

		// Should fail for objects
		$obj = new \StdClass();
		$this->assertFalse(Arr::is($obj));
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsMultipleSucceed()
	{
		$this->assertTrue(Arr::is([1], [3]));
	}

	/**
   * @covers \Nerd\Arr::is
   */
	public function testArrIsMultipleFail()
	{
		$this->assertFalse(Arr::is([1], 'string'));
	}

	/**
   * @covers \Nerd\Arr::toEnumerable
   */
	public function testArrToEnum()
	{
		$enum = Arr::toEnumerable([1, 2, 3]);

		$this->assertTrue($enum instanceof \Nerd\Design\Enumerable);
	}

	/**
   * @covers \Nerd\Arr::toObject
   */
	public function testArrToObjectSucceed()
	{
		// Should succeed on an assoc array
		$obj = Arr::toObject(['test' => 'one']);
		$this->assertTrue(is_object($obj));
	}

	/**
   * @covers \Nerd\Arr::toObject
   */
	public function testArrToObjectFail()
	{
		// Should fail on a normal array
		$obj = Arr::toObject([1,2,3]);
		$this->assertFalse(is_object($obj));
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
