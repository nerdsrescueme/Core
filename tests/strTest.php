<?php

namespace Nerd;

class StrTest extends TestCase
{
    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Str');
    }

    /**
     * The Str class should reside within the Nerd namespace
     * 
     * @covers \Nerd\Str
     */
    public function testStrInNerdNamespace()
    {
       $this->assertEquals($this->ref->getNamespaceName(), 'Nerd');
    }

    /**
     * Str class should contain an initialization method
     * 
     * @covers \Nerd\Str::__initialize
     */
    public function testStrHasInitializer()
    {
        $message = 'Str does not contain an __initialize method';
        $result  = $this->ref->hasMethod('__initialize');

        $this->assertTrue($result, $message);

        $message = 'Str does not implement the Initializable class';
        $result  = $this->ref->implementsInterface('\\Nerd\\Design\\Initializable');

        $this->assertTrue($result, $message);
    }

    /**
     * Str class should not contain a constructor
     * 
     * @covers \Nerd\Str
     */
    public function testStrIsUninstantiable()
    {
        $this->assertFalse($this->ref->hasMethod('__construct'));
    }

    /**
     * All methods in Str should be declared as static classes
     * 
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
     * Str::lower should result in a fully lowercase string
     * 
     * @covers \Nerd\Str::lower
     */
    public function testStrLower()
    {
        $message  = 'Str::lower cannot convert a value to lower case';
        $result   = Str::lower('NaMe');
        $expected = 'name';

        $this->assertEquals($result, $expected, $message);

        $message  = 'Str::lower does not output a string value';

        $this->assertString($result, $message);
    }

    /**
     * Str::upper should result in a fully uppercase string
     * 
     * @covers \Nerd\Str::upper
     */
    public function testStrUpper()
    {
        $message  = 'Str::upper cannot convert a value to upper case';
        $result   = Str::upper('NaMe');
        $expected = 'NAME';

        $this->assertEquals($result, $expected, $message);

        $message  = 'Str::upper does not output a string value';

        $this->assertString($result);
    }

    /**
     * Str::length should output the length of the input string
     * 
     * @covers \Nerd\Str::length
     */
    public function testStrLength()
    {
        $message  = 'Str::length cannot count the characters in a string properly';
        $result   = Str::length('name');
        $expected = 4;

        $this->assertEquals($result, $expected, $message);

        $message  = 'Str::length does not output an integer';

        $this->assertInteger($result, $message);
    }

    /**
     * Str::sub should output a substring of the input
     * 
     * @covers \Nerd\Str::sub
     */
    public function testStrSub()
    {
        $message  = 'Str::sub cannot output a correct substring';
        $result   = Str::sub('name', 0, 2);
        $expected = 'na';

        $this->assertEquals($result, $expected, $message);

        $message  = 'Str::sub does not output a string value';

        $this->assertString($result, $message);
    }

    /**
     * Str should have its static mbString property set upon instantiation
     * 
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
     * Str::is should succeed when it receives a single string value
     * 
     * @covers \Nerd\Str::is
     */
    public function testStrIsSucceeds()
    {
        $message = 'Str::is does not properly identify a single string argument';
        $result  = Str::is('string');

        $this->assertTrue($result, $message);
    }

    /**
     * Str::is should succeed when it receives multiple string values
     * 
     * @covers \Nerd\Str::is
     */
    public function testStrIsMultipleSucceeds()
    {
        $message = 'Str::is does not properly identify multiple string arguments';
        $result  = Str::is('string', 'second string');

        $this->assertTrue($result, $message);
    }

    /**
     * Str::is should fail when receiving a non-string value as an argument
     * 
     * @covers \Nerd\Str::is
     */
    public function testStrIsFails()
    {
        $message = 'Str::is does not return false when receiving non-string values';

        $result  = Str::is(123456);
        $this->assertFalse($result, $message);

        $result  = Str::is(new \stdClass());
        $this->assertFalse($result, $message);

        $result  = Str::is(true);
        $this->assertFalse($result, $message);
    }

    /**
     * Str::is should fail when receiving ANY non-string values
     *
     * @covers \Nerd\Str::is
     */
    public function testStrIsMultipleFails()
    {
        $message = 'Str::is does not fail when receiving a single string and another type of value';

        $result  = Str::is('string', 123456);
        $this->assertFalse($result, $message);

        $result  = Str::is(true, 112365, 'string');
        $this->assertFalse($result, $message);
    }

    /**
     * Str::is should return a boolean value when it succeeds
     *
     * @depends testStrIsSucceeds
     * @covers \Nerd\Str::is
     */
    public function testStrIsReturnsBooleanOnSuccess()
    {
        $message = 'Str::is does not return a boolean value on success';
        $result  = Str::is('string');

        $this->assertBoolean($result, $message);
    }

    /**
     * Str::is should return a boolean value when it fails
     *
     * @depends testStrIsFails
     * @covers \Nerd\Str::is
     */
    public function testStrIsReturnsBooleanOnFailure()
    {
        $message = 'Str::is does not return a boolean value on failure';
        $result  = Str::is(1234856);

        $this->assertBoolean($result, $message);
    }

    /**
     * Str::abbreviate should remove all vowels from a string
     * 
     * @covers \Nerd\Str::abbreviate
     */
    public function testStrAbbreviate()
    {
        $message  = 'Str::abbreviate does not remove all vowels from a string';
        $result   = Str::abbreviate('station');
        $expected = 'sttn';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Str::abbreviate should return a string value
     *
     * @covers \Nerd\Str::abbreviate
     */
    public function testStrAbbreviateReturnsString()
    {
        $message = 'Str::abbreviate does not return a string value';
        $result  = Str::abbreviate('station');

        $this->assertString($result, $message);
    }

    /**
     * Str::title should return a title cased string
     * 
     * @covers \Nerd\Str::title
     */
    public function testStrTitle()
    {
        $message  = 'Str::title does not return a proper title cased string';
        $result   = Str::title('my string title');
        $expected = 'My String Title';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Str::title should return a string value
     *
     * @covers \Nerd\Str::title
     */
    public function testStrTitleReturnsString()
    {
        $message = 'Str::title does not return a string value';
        $result  = Str::title('my string title');

        $this->assertString($result, $message);
    }

    /**
     * Str::humanize should return a human readable string
     * 
     * @covers \Nerd\Str::humanize
     */
    public function testStrHumanize()
    {
        $message  = 'Str::humanize does not return a proper human readable string';
        $result   = Str::humanize('my_human_string');
        $expected = 'My Human String';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Str::humanize should return a string value
     *
     * @covers \Nerd\Str::humanize
     */
    public function testStrHumanizeReturnsString()
    {
        $message = 'Str::humanize does not return a string value';
        $result  = Str::humanize('my_human_string');

        $this->assertString($result, $message);
    }

    /**
     * Str::random should return a string of the requested length
     * 
     * @covers \Nerd\Str::random
     */
    public function testStrRandomCorrectLength()
    {
        $message  = 'Str::random does not return the requested length string';
        $length   = 16;
        $result   = strlen(Str::random($length));

        $this->assertEquals($length, $result, $message);
    }

    /**
     * NEED TO WRITE SOMETHING
     * 
     * @covers \Nerd\Str::pool
     */
    public function testStrPool()
    {
        // Need to write some regex's to match the output.
        $this->assertTrue(true);
    }
}
