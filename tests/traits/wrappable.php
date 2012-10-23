<?php

trait Wrappable
{
	/**
	 * Does the testable class implement the wrappable trait?
	 */
	public function testImplementsWrappable()
	{
		$this->assertTrue($this->ref->hasMethod('wrap'));
	}

	/**
	 * Are the methods chainable?
	 */
	public function testWrapMethodIsChainable()
	{
		$this->assertEquals($this->ins, $this->ins->wrap(false));
	}

	/**
	 * Are the methods chainable?
	 */
	public function testWrapFieldsMethodIsChainable()
	{
		$this->assertEquals($this->ins, $this->ins->wrapFields(false));
	}

	/**
	 * Does hasWrap return a boolean value?
	 */
	public function testHasWrapReturnsBoolean()
	{
		$this->assertTrue(is_bool($this->ins->hasWrap()));
	}

	/**
	 * Does hasFieldWrap return a boolean value?
	 */
	public function testHasFieldWrapReturnsBoolean()
	{
		$this->assertTrue(is_bool($this->ins->hasFieldWrap()));
	}

	/**
	 * Does setting a wrap succeed?
	 *
	 * @depends testHasWrapReturnsBoolean
	 */
	public function testWrapSucceeds()
	{
		$this->ins->wrap('<divtest>', '</divtest>');

		if ($this->ins->type === 'hidden') {
			// All hidden fields should fail.
			$this->assertFalse($this->ins->hasWrap());
		} else {
			$this->assertTrue($this->ins->hasWrap());
		}
	}

	/**
	 * Does setting a fieldWrap succeed?
	 *
	 * @depends testHasFieldWrapReturnsBoolean
	 */
	public function testFieldWrapSucceeds()
	{
		$this->ins->wrapFields('<divtest>', '</divtest>');

		if ($this->ins->type === 'hidden') {
			// All hidden fields should fail.
			$this->assertFalse($this->ins->hasFieldWrap());
		} else {
			$this->assertTrue($this->ins->hasFieldWrap());
		}
	}
}