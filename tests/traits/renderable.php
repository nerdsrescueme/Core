<?php

trait Renderable
{
	/**
	 * Does the class instance have a render() method?
	 */
	public function testHasRenderMethod()
	{
		if (!property_exists($this, 'ref') or !is_object($this->ref)) {
			$this->fail('This test does not implement $this->ref class reflection.');
		}

		$this->assertTrue($this->ref->hasMethod('render'));
	}

	/**
	 * Does the class instance have a __toString() method?
	 */
	public function testHasStringRenderMethod()
	{
		if (!property_exists($this, 'ref') or !is_object($this->ref)) {
			$this->fail('This test does not implement $this->ref class reflection.');
		}

		$this->assertTrue($this->ref->hasMethod('__toString'));
	}

	/**
	 * Does the class instance render as a string?
	 *
	 * @depends testHasRenderMethod
	 * @depends testHasStringRenderMethod
	 */
	public function testCanRenderString()
	{
		if (!property_exists($this, 'ins') or !is_object($this->ins)) {
			$this->fail('This test does not implement $this->ins class instance.');
		}

		$this->assertTrue(is_string($this->ins->render()));
		$this->assertTrue(is_string((string) $this->ins));
	}
}