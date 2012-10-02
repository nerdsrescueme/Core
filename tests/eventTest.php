<?php

use \Nerd\Event;

class EventTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->event = Event::instance();
        $this->event->bind('test.bind', function() { $i = 0; });
    }

    /**
     * @covers \Nerd\Event
     */
    public function testEventSingleton()
    {
        $this-assertSame($this->event, Event::instance());
    }

    /**
     * @covers \Nerd\Event::bind
     */
    public function testEventBind()
    {
       $this->assertTrue($this->event->bind('test.bind', function() { $i = 0; }));
    }

    /**
     * @covers \Nerd\Event::bind
     */
    public function testEventMultipleBind()
    {
       $this->assertTrue($this->event->bind('test.bind', function() { $i = 0; }));
       $this->assertTrue($this->event->bind('test.bind', function() { $i = 1; }));
    }

    /**
     * @covers \Nerd\Event::bind
     */
    public function testEventBindReturnsBoolean()
    {
       $this->assertTrue(is_bool($this->event->bind('test.bind', function() { $i = 0; })));
    }

    /**
     * @covers \Nerd\Event::unbind
     */
    public function testEventUnbind()
    {
        $this->assertTrue($this->event->unbind('test.bind'));
    }

    /**
     * @covers \Nerd\Event::unbind
     */
    public function testEventUnbindReturnsBoolean()
    {
        $this->assertTrue(is_bool($this->event->unbind('test.bind')));
    }

    /**
     * @covers \Nerd\Event::trigger
     */
    public function testEventTrigger()
    {
        $this->assertTrue($this->event->trigger('test.bind'));
    }

    /**
     * @covers \Nerd\Event::trigger
     */
    public function testEventTriggerReturnsBoolean()
    {
        $this->assertTrue(is_bool($this->event->trigger('test.bind')));
    }
    
}