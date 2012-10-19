<?php

use \Nerd\Autoloader;

class AutoloaderTest extends \PHPUnit_Framework_TestCase
{
    // We kind of just have to trust that register and unregister work...
    // Not sure how to test that.

    protected $ref;
	protected $al;

    public function setUp()
    {
        $this->ref = new \ReflectionClass('\\Nerd\\Autoloader');
		$this->al  = new \Nerd\Autoloader();
    }

    /**
     * @covers \Nerd\Autoloader
     */
    public function testAutoloaderInNerdNamespace()
    {
       $this->assertEquals($this->ref->getNamespaceName(), 'Nerd');
    }

    /**
     * @covers \Nerd\Autoloader::denamespace
     */
    public function testAutoloaderDenamespace()
    {
        $actual = $this->al->denamespace('\\Nerd\\Test\\Klass');
        $this->assertEquals($actual, 'Klass', 'Autoloader::denamespace cannot determine the correct class name');
    }

    /**
     * @covers \Nerd\Autoloader::denamespace
     */
    public function testAutoloaderDenamespaceReturnsString()
    {
        $actual = Autoloader::denamespace('\\Nerd\\Test\\Klass');
        $this->assertTrue(is_string($actual), 'Autoloader::denamespace does not return a string value');
    }

    /**
     * @covers \Nerd\Autoloader::exists
     */
    public function testAutoloaderExistsSuccess()
    {
        $result = $this->al->exists('\\Nerd\\Autoloader');
        $this->assertTrue($result, 'Autoloader::exists can not determine if a class exists');
    }

    /**
     * @covers \Nerd\Autoloader::exists
     * @depends testAutoloaderExistsSuccess
     */
    public function testAutoloaderExistsSuccessReturnsBoolean()
    {
        $result = $this->al->exists('\\Nerd\\Autoloader');
        $this->assertTrue(is_bool($result), 'Autoloader::exists does not return a boolean value on success');
    }

    /**
     * @covers \Nerd\Autoloader::exists
     */
    public function testAutoloaderExistsFail()
    {
        $result = $this->al->exists('\\Nerd\\Shouldfail');
        $this->assertFalse($result, 'Autoloader::exists falsely identifies classes that do not exist');
    }

    /**
     * @covers \Nerd\Autoloader::exists
     * @depends testAutoloaderExistsFail
     */
    public function testAutoloaderExistsFailReturnsBoolean()
    {
        $result = $this->al->exists('\\Nerd\\Shouldfail');
        $this->assertTrue(is_bool($result), 'Autoloader::exists does not return a boolean value on failure');
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadSucceed()
    {
        $result = $this->al->load('\\Nerd\\Autoloader');
        $this->assertTrue($result, 'Autoloader::load can not load an existing class');
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadSuccessReturnsBoolean()
    {
        $result = $this->al->load('\\Nerd\\Autoloader');
        $this->assertTrue(is_bool($result), 'Autoloader::load does not return a boolean value on success');
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadFail()
    {
        $result = $this->al->load('\\Nerd\\ShouldFail');
        $this->assertFalse($result, 'Autoloader::load falsely reports it can load a class that does not exist');
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadFailReturnsBoolean()
    {
        $result = $this->al->load('\\Nerd\\ShouldFail');
        $this->assertTrue(is_bool($result), 'Autoloader::load does not return a boolean value on failure');
    }
}
