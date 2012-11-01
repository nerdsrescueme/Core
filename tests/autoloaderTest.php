<?php

namespace Nerd;

class AutoloaderTest extends TestCase
{
    // We kind of just have to trust that register and unregister work...
    // Not sure how to test that.

    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Autoloader');
		$this->setUpInstance(new \Nerd\Autoloader());
    }

    /**
	 * Class should live within the Nerd namespace
	 * 
     * @covers \Nerd\Autoloader
     */
    public function testAutoloaderInNerdNamespace()
    {
		$message  = 'The Autoloader does not live within the Nerd namespace';
		$result   = $this->ref->getNamespaceName();
		$expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
	 * Should determine the correct class name
	 * 
     * @covers \Nerd\Autoloader::denamespace
     */
    public function testAutoloaderDenamespace()
    {
		$message  = 'Autoloader::denamespace cannot determine the correct class name';
        $result   = $this->ins->denamespace('\\Nerd\\Test\\Klass');
		$expected = 'Klass';

        $this->assertEquals($result, $expected, $message);
    }

    /**
	 * Should return a string value
	 * 
     * @covers \Nerd\Autoloader::denamespace
     */
    public function testAutoloaderDenamespaceReturnsString()
    {
		$message = 'Autoloader::denamespace does not return a string value';
        $result  = Autoloader::denamespace('\\Nerd\\Test\\Klass');

        $this->assertString($result, $message);
    }

    /**
	 * Should determine that a valid class exists
	 * 
     * @covers \Nerd\Autoloader::exists
     */
    public function testAutoloaderExistsSuccess()
    {
		$message = 'Autoloader::exists can not determine if a class exists';
        $result  = $this->ins->exists('\\Nerd\\Autoloader');

        $this->assertTrue($result, $message);
    }

    /**
	 * Should return a boolean value
	 * 
     * @covers \Nerd\Autoloader::exists
     * @depends testAutoloaderExistsSuccess
     */
    public function testAutoloaderExistsSuccessReturnsBoolean()
    {
		$message = 'Autoloader::exists does not return a boolean value on success';
        $result  = $this->ins->exists('\\Nerd\\Autoloader');

        $this->assertBoolean($result, $message);
    }

    /**
	 * Should fail when an invalid class is passed
	 * 
     * @covers \Nerd\Autoloader::exists
     */
    public function testAutoloaderExistsFail()
    {
		$message = 'Autoloader::exists falsely identifies classes that do not exist';
        $result  = $this->ins->exists('\\Nerd\\Shouldfail');

        $this->assertFalse($result, $message);
    }

    /**
	 * Should return a boolean value
	 * 
     * @covers \Nerd\Autoloader::exists
     * @depends testAutoloaderExistsFail
     */
    public function testAutoloaderExistsFailReturnsBoolean()
    {
		$message = 'Autoloader::exists does not return a boolean value on failure';
        $result  = $this->ins->exists('\\Nerd\\Shouldfail');

        $this->assertBoolean($result, $message);
    }

    /**
	 * Should succeed in loading an existing class
	 * 
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadSucceed()
    {
		$message = 'Autoloader::load can not load an existing class';
        $result  = $this->ins->load('\\Nerd\\Autoloader');

        $this->assertTrue($result, $message);
    }

    /**
	 * Should return a boolean value
	 * 
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadSuccessReturnsBoolean()
    {
		$message = 'Autoloader::load does not return a boolean value on success';
        $result  = $this->ins->load('\\Nerd\\Autoloader');

        $this->assertBoolean($result, $message);
    }

    /**
	 * Should fail when loading a non existing class
	 * 
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadFail()
    {
		$message = 'Autoloader::load falsely reports it can load a class that does not exist';
        $result  = $this->ins->load('\\Nerd\\ShouldFail');

        $this->assertFalse($result, $message);
    }

    /**
	 * Should return a boolean value
	 * 
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadFailReturnsBoolean()
    {
		$message = 'Autoloader::load does not return a boolean value on failure';
        $result  = $this->ins->load('\\Nerd\\ShouldFail');

        $this->assertBoolean($result, $message);
    }
}
