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
        $this->assertEquals(Autoloader::denamespace('\\Nerd\\Test\\Klass'), 'Klass');
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
        $this->assertFalse(Autoloader::exists('\\Nerd\\Shouldfail'));
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadSucceed()
    {
        $this->assertTrue(\Nerd\Autoloader::load('\\Nerd\\Autoloader'));
    }

    /**
     * @covers \Nerd\Autoloader::load
     */
    public function testAutoloaderLoadFail()
    {
        $this->assertFalse(\Nerd\Autoloader::load('\\Nerd\\ShouldFail'));
    }
}
