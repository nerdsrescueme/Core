<?php

namespace Nerd;

class StrTest extends TestCase
{
    protected $ref;

    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Str');
    }

    /**
     * @covers \Nerd\Str
     */
    public function testStrInNerdNamespace()
    {
       $this->assertEquals($this->ref->getNamespaceName(), 'Nerd');
    }

    /**
     * @covers \Nerd\Str
     */
    public function testStrIsUninstantiable()
    {
        $this->assertFalse($this->ref->hasMethod('__construct'));
    }

    /**
     * @covers \Nerd\Str
     */
    public function testStrAllMethodsStatic()
    {
        $methods = $this->ref->getMethods();
        $this->assertNotEmpty($methods, 'Str does not contain any methods');

        foreach ($methods as $method) {
            $this->assertTrue($method->isStatic(), 'Str::'.$method->getName().' is not a static method');
        }
    }

    /**
     * @covers \Nerd\Str::lower
     */
    public function testStrLower()
    {
        $message  = 'Str::lower cannot convert a value to lower case';
        $result   = Str::lower('NaMe');
        $expected = 'name';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Str::upper
     */
    public function testStrUpper()
    {
        $message  = 'Str::upper cannot convert a value to upper case';
        $result   = Str::upper('NaMe');
        $expected = 'NAME';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Str::length
     */
    public function testStrLength()
    {
        $message  = 'Str::length cannot count the characters in a string properly';
        $result   = Str::length('name');
        $expected = 4;

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Str::sub
     */
    public function testStrSub()
    {
        $this->assertEquals('na', Str::sub('name', 0, 2));
    }

    /**
     * @covers \Nerd\Str
     */
    public function testStrMbstringSet()
    {
        $message  = 'Str::$mbString is not being set properly';
        $result   = function_exists('mb_get_info');
        $expected = Str::$mbString;

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Str::__callStatic
     */
    public function testStrCanBeCalledStatically()
    {
        $this->assertTrue($this->ref->hasMethod('__callStatic'));
        $this->assertEquals(Str::__callStatic('lower', ['NAME']), 'name');
    }

    /**
     * @covers \Nerd\Str::is
     */
    public function testStrIsSucceeds()
    {
        $this->assertTrue(Str::is('string'));
    }

    /**
     * @covers \Nerd\Str::is
     */
    public function testStrIsMultipleSucceeds()
    {
        $this->assertTrue(Str::is('string', 'second string'));
    }

    /**
     * @covers \Nerd\Str::is
     */
    public function testStrIsFails()
    {
        $this->assertFalse(str::is(123456));
        $this->assertFalse(Str::is(new \stdClass()));
    }

    /**
     * @covers \Nerd\Str::is
     */
    public function testStrIsMultipleFails()
    {
        $this->assertFalse(Str::is('string', 123456));
        $this->assertFalse(Str::is(new \stdClass(), 65489));
    }

    /**
     * @covers \Nerd\Str::is
     */
    public function testStrIsReturnsBoolean()
    {
        $this->assertTrue(is_bool(Str::is('string')));
        $this->assertTrue(is_bool(Str::is(['1', 2])));
    }

    /**
     * @covers \Nerd\Str::abbreviate
     */
    public function testStrAbbreviate()
    {
        $this->assertEquals('sttn', Str::abbreviate('station'));
    }

    /**
     * @covers \Nerd\Str::title
     */
    public function testStrTitle()
    {
        $this->assertEquals(Str::title('my string title'), 'My String Title');
    }

    /**
     * @covers \Nerd\Str::humanize
     */
    public function testStrHumanize()
    {
        $this->assertEquals(Str::humanize('my_human_string'), 'My Human String');
    }

    /**
     * @covers \Nerd\Str::random
     */
    public function testStrRandomCorrectLength()
    {
        $length = 16;
        $random = Str::random($length);

        $this->assertEquals($length, strlen($random));
    }

    /**
     * @covers \Nerd\Str::pool
     */
    public function testStrPool()
    {
        // Need to write some regex's to match the output.
        $this->assertTrue(true);
    }

    /**
     * @covers \Nerd\Str::__initialize
     */
    public function testStrHasInitializer()
    {
        $this->assertTrue($this->ref->hasMethod('__initialize'));
        $this->assertTrue($this->ref->implementsInterface('\\Nerd\\Design\\Initializable'));
    }
}
