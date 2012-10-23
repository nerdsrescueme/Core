<?php

trait Attributable
{
	/**
	 * Does the testable class implement the attributable trait?
	 */
	public function testImplementsAttributable()
	{
		$this->assertTrue($this->ref->hasMethod('attributes'));
	}

	/**
	 * Can we get and set an attribute on the class instance?
	 */
	public function testCanSetGetAttribute()
	{
		$this->ins->id = 'test';
		$this->assertEquals($this->ins->id, 'test');
	}

	/**
	 * Can we check isset() for an attribute on the class instance?
	 *
	 * @depends testCanSetGetAttribute
	 */
	public function testCanTestAttributeIsSet()
	{
		$this->ins->id = 'test';
		$this->assertTrue(isset($this->ins->id));
	}

	/**
	 * Can we get and set an attribute using a dynamic method?
	 */
	public function testCanSetGetClassMethodAttribute()
	{
		$this->ins->class('test');
		$this->assertEquals($this->ins->class, 'test');
	}

	/**
	 * Can we check isset() for an attribute set using a dynamic method?
	 * 
	 * @depends testCanSetGetClassMethodAttribute
	 */
	public function testCanTestClassAttributeIsSet()
	{
		$this->ins->class('test');
		$this->assertTrue(isset($this->ins->class));
	}

	/**
	 * Are we able to set a data attribute?
	 */
	public function testCanSetDataAttribute()
	{
		// Need retrieval method
		$this->ins->data('test', 'value');
		$this->assertTrue(true);
	}

	/**
	 * Does allowedAttributes return an array?
	 */
	public function testAllowedAttributesReturnsArray()
	{
		$class = $this->ref->getName();
		$this->assertTrue(is_array($class::allowedAttributes()));
	}

	/**
	 * Does $class->attributes() return an array?
	 */
	public function testAttributesMethodReturnsArray()
	{
		$this->assertTrue(is_array($this->ins->attributes()));
	}

	/**
	 * Does $class->attributes(true) return an array?
	 */
	public function testAttributesMethodReturnsString()
	{
		$this->assertTrue(is_string($this->ins->attributes(true)));
	}
}