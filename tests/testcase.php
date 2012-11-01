<?php

namespace Nerd;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Hold an reflection of the class of which we are testing
     *
     * @var \ReflectionClass
     */
    protected $ref;

    /**
     * Default fixture property
     *
     * @var mixed
     */
    protected $fixture;

    /**
     * Hold an instance of the class of which we are testing
     *
     * @var object
     */
    protected $ins;


    public function setUpReflection($class)
    {
        $this->ref = new \ReflectionClass($class);
    }

    public function setUpInstance($instance)
    {
        $this->ins = $instance;
    }

    // Test for type

    public function assertArray($item, $message = null)
    {
        $this->assertTrue(\is_array($item), $message);
    }

    public function assertString($item, $message = null)
    {
        $this->assertTrue(\is_string($item), $message);
    }

    public function assertBoolean($item, $message = null)
    {
        $this->assertTrue(\is_bool($item), $message);
    }

    public function assertObject($item, $message = null)
    {
        $this->assertTrue(\is_object($item), $message);
    }

    // Test not type

    public function assertNotArray($item, $message = null)
    {
        $this->assertFalse(\is_array($item), $message);
    }

    public function assertNotString($item, $message = null)
    {
        $this->assertFalse(\is_string($item), $message);
    }

    public function assertNotBoolean($item, $message = null)
    {
        $this->assertFalse(\is_bool($item), $message);
    }

    public function assertNotObject($item, $message = null)
    {
        $this->assertFalse(\is_object($item), $message);
    }
}