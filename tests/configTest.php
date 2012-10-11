<?php

namespace Nerd;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    protected $ref;

    public function setUp()
    {
        $this->ref = new \ReflectionClass('\\Nerd\\Config');
    }

	/**
     * @covers \Nerd\Config
     */
    public function testConfigInNerdNamespace()
    {
	   $this->assertEquals($this->ref->getNamespaceName(), 'Nerd', 'Config is not defined in the Nerd namespace');
    }

    /**
     * @covers \Nerd\Config
     */
    public function testConfigDotparsable()
    {
	   $this->assertArrayHasKey('Nerd\\Design\\Dotparser', $this->ref->getTraits(), 'Config does not include the dotparser trait');
    }

	/**
	 * @covers \Nerd\Config
	 */
	public function testConfigIsUninstantiable()
	{
		$this->assertFalse($this->ref->hasMethod('__construct'));
	}

	/**
	 * @covers \Nerd\Config
	 */
	public function testConfigAllMethodsStatic()
	{
		$methods = $this->ref->getMethods();
		$this->assertNotEmpty($methods, 'Config does not contain any methods');

		foreach($methods as $method)
		{
			$this->assertTrue($method->isStatic(), 'Config::'.$method->getName().' is not a static method');
		}
	}

	/**
	 * @covers \Nerd\Config::load
	 */
	public function testConfigLoad()
	{
		// try loading main application config variable to test.
		$this->assertNotNull(Config::get('error.reporting'), 'Config::load is unable to load a file from the config folder');
	}

	/**
	 * @covers \Nerd\Config::get
	 */
	public function testConfigGetDefaultEmpty()
	{
		$this->assertNull(Config::get('nonexistent'), 'Config::get does not return null when no default is given');
	}

	/**
	 * @covers \Nerd\Config::get
	 */
	public function testConfigGetNamespaced()
	{
		// Load the test config from geek...
		$this->assertEquals(Config::get('geek::test.key'), 'value', 'Config::get can not retrieve a namespaced config file');
	}

	/**
	 * @covers \Nerd\Config::get
	 */
	public function testConfigDefaultWhenNotEmpty()
	{
		$expected = 'default';
		$this->assertEquals(Config::get('nonexistent', 'default'), $expected, 'Config::get does not return a default value when provided');
	}

	/**
	 * @covers \Nerd\Config::get
	 */
	public function testConfigDefaultClosure()
	{
		$closure = function() { return true; };
		$this->assertTrue(Config::get('nonexistent', $closure), 'Config::get does not execute a closure when provided as a default value');
	}

	/**
	 * @covers \Nerd\Config::set
	 */
	public function testConfigSet()
	{
		Config::set('error.new', 'mynewvalue');
		$this->assertEquals(Config::get('error.new'), 'mynewvalue', 'Config::set does not set a new value');
	}

	/**
	 * @covers \Nerd\Config::set
	 */
	public function testConfigSetNamespaced()
	{
		Config::set('geek::test.key2', 'value');
		$this->assertEquals(Config::get('geek::test.key2'), 'value', 'Config::set does not set a namespaced value');
	}

	/**
	 * @covers \Nerd\Config::set
	 * @expectedException \OutOfBoundsException
	 */
	public function testConfigSetFail()
	{
		Config::set('nonexistent.key', 'value');
	}
}