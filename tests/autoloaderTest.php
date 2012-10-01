<?php

use \Nerd\Autoloader;

class AutoloaderTest extends PHPUnit_Framework_TestCase
{
    // We kind of just have to trust that register and unregister work...
    // Not sure how to test that.

    /**
     * @covers \Nerd\Autoloader::denamespace
     */
    public function testAutoloaderDenamespace()
    {
        $actual = '\\Nerd\\Test\\Klass';
        $this->assertEquals(Autoloader::denamespace($actual), 'Klass');
    }

    /**
     * @covers \Nerd\Autoloader::exists
     */
    public function testAutoloaderExistsSuccess()
    {
        $this->assertTrue(Autoloader::exists('\\Nerd\\Autoloader'));
    }

    /**
     * @covers \Nerd\Autoloader::exists
     */
    public function testAutoloaderExistsFail()
    {
        $this->assertFalse(Autoloader::exists('Shouldfail'));
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadSucceed()
    {
        // Need to test
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadFail()
    {
        // Need to test
    }

    /**
     * @covers \Nerd\Autoloader::register
     */
    public function testAutoloaderRegister()
    {
        $this->assertTrue(Autoloader::register());
    }

    /**
     * @covers \Nerd\Autoloader::unregister
     */
    public function testAutoloaderUnregister()
    {
        $this->assertTrue(Autoloader::unregister());
    }
}
