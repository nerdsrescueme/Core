<?php

namespace Nerd;

class ArrTest extends TestCase
{
    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Arr');

        $this->fixture = [
            'Frank' => [
                'id' => 123,
                'email' => 'frank@test.com'
            ],
            'Antoine' => [
                'id' => 456,
                'email' => 'antoine@test.com'
            ],
        ];
    }

    /**
     * The Arr class should live in the Nerd namespace
     * 
     * @covers \Nerd\Arr
     */
    public function testArrInNerdNamespace()
    {
        $message  = 'Arr class is not declared in the Nerd namespace';
        $result   = $this->ref->getNamespaceName();
        $expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Make sure the fixture is valid for these tests
     * 
     * @coversNothing
     */
    public function testArrFixtureIsValid()
    {
        $message  = 'The Arr test fixture has changed, correct this problem';
        $expected = 2;

        $this->assertCount($expected, $this->fixture, "$message: count() should be 2");

        // ... 
    }

    /**
     * @covers \Nerd\Arr
     */
    public function testArrAllMethodsStatic()
    {
        $message = 'Arr does not contain any methods';
        $methods = $this->ref->getMethods();

        $this->assertNotEmpty($methods, $message);

        foreach ($methods as $method) {
            $message = 'Arr::'.$method->getName().' is not a static method';
            $result  = $method->isStatic();

            $this->assertTrue($result, $message);
        }
    }

    /**
     * @covers \Nerd\Arr::get
     * @depends testArrFixtureIsValid
     */
    public function testArrGet()
    {
        $message  = 'Arr::get cannot retrieve values';
        $result   = Arr::get($this->fixture, 'Frank.id');
        $expected = 123;

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Arr::get
     * @depends testArrFixtureIsValid
     */
    public function testArrGetDefaultToNull()
    {
        $message = 'Arr::get does not default to null when no $default is provided';
        $result  = Arr::get($this->fixture, 'nonexistent.key');

        $this->assertNull($result, $message);
    }

    /**
     * @covers \Nerd\Arr::get
     * @depends testArrFixtureIsValid
     */
    public function testArrGetDefaultWhenProvided()
    {
        $message  = 'Arr::get does not return $default value when no key is present';
        $result   = Arr::get($this->fixture, 'nonexistent.key', 'default');
        $expected = 'default';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Arr::get
     * @depends testArrGetDefaultWhenProvided
     * @depends testArrFixtureIsValid
     */
    public function testArrGetDefaultClosureIsInvoked()
    {
        $message = 'Arr::get does not invoke a closure based default argument';
        $result  = Arr::get($this->fixture, 'nonexistent.key', function(){return true;});

        $this->assertTrue($result, $message);
    }

    /**
     * @covers \Nerd\Arr::delete
     * @depends testArrFixtureIsValid
     */
    public function testArrDelete()
    {
        $message  = 'Arr::delete does not delete from referenced array';
        $expected = 1;

        Arr::delete($this->fixture, 'Frank'); // Remove one key
        $this->assertCount($expected, $this->fixture, $message);
    }

    /**
     * @covers \Nerd\Arr::set
     * @depends testArrFixtureIsValid
     */
    public function testArrSet()
    {
        $message  = 'Arr::set does not set a value on the referenced array';
        $expected = 3;

        Arr::set($this->fixture, 'NewUser', 'testdata');
        $this->assertCount($expected, $this->fixture, $message);
    }

    /**
     * @covers \Nerd\Arr::has
     * @depends testArrFixtureIsValid
     */
    public function testArrHasSuccess()
    {
        $message = 'Arr::has cannot determine if an array has an available key';
        $result  = Arr::has($this->fixture, 'Frank');

        $this->assertTrue($result, $message);
    }

    /**
     * @covers \Nerd\Arr::has
     * @depends testArrFixtureIsValid
     */
    public function testArrHasFail()
    {
        $message = 'Arr::has finds array keys that do not exist';
        $result  = Arr::has($this->fixture, 'nonexistent');

        $this->assertFalse($result, $message);
    }

    /**
     * @covers \Nerd\Arr::operate
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testArrOperateNeedsArrayForArg1()
    {
        $var = 'string'; // Must be passed by reference

        Arr::operate($var, function(){});
    }

    /**
     * @covers \Nerd\Arr::operate
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testArrOperateNeedsCallableForArg2()
    {
        Arr::operate($this->fixture, 'notcallable');
    }

    /**
     * @covers \Nerd\Arr::operate
     * @depends testArrFixtureIsValid
     */
    public function testArrOperateAcceptsArrayForArg1()
    {
        $message = 'Arr::operate does not accept an array as its first argument';
        $result  = Arr::operate($this->fixture, function($arr) { return true; });

        $this->assertTrue($result, $message);
    }

    /**
     * @covers \Nerd\Arr::operate
     * @depends testArrFixtureIsValid
     */
    public function testArrOperateAcceptsClosureForArg2()
    {
        $message = 'Arr::operate cannot execute a closure callable';
        $result  = Arr::operate($this->fixture, function($arr) { return true; });

        $this->assertTrue($result, $message);
    }

    /**
     * @covers \Nerd\Arr::operate
     * @depends testArrFixtureIsValid
     */
    public function testArrOperateAcceptsStringCallableForArg2()
    {
        $message = 'Arr::oprate cannot execute a string callable';
        $result  = Arr::operate($this->fixture, 'array_pop');

        $this->assertArray($result, $message);
    }

    /**
     * @covers \Nerd\Arr::operate
     * @depends testArrFixtureIsValid
     */
    public function testArrOperateSimpleTest()
    {
        $message  = 'Arr::operate cannot perform a simple test';
        $expected = end($this->fixture);
        $result   = Arr::operate($this->fixture, function($arr) { return end($arr); });
        
        $this->assertEquals($expected, $result, $message);
    }

    /**
     * @covers \Nerd\Arr::is
     */
    public function testArrIsSuccess()
    {
        $message = 'Arr::is is unable to properly determine if it had an array passed to it';
        $result  = Arr::is( [1] );

        $this->assertTrue($result, $message);
    }

    /**
     * @covers \Nerd\Arr::is
     */
    public function testArrIsFail()
    {
        // refactor

        $this->assertFalse(Arr::is('string'), 'Arr::is believes that strings are arrays');
        $this->assertFalse(Arr::is(123), 'Arr::is believes that integers are arrays');
        $this->assertFalse(Arr::is(false), 'Arr::is believes that booleans are arrays');
        $this->assertFalse(Arr::is(new \StdClass()), 'Arr::is believes that objects are arrays');
    }

    /**
     * Arr::is should be able to check if ALL arguments are arrays
     * 
     * @covers \Nerd\Arr::is
     */
    public function testArrIsMultipleSucceed()
    {
        $message = 'Arr::is is unable to determine if it had multiple arrays passed to it';
        $result  = Arr::is( [1], [2] );

        $this->assertTrue($result, $message);
    }

    /**
     * Arr::is should fail if ANY of the arguments passed are not an array
     * 
     * @covers \Nerd\Arr::is
     */
    public function testArrIsMultipleFail()
    {
        $message = 'Arr::is does not fail if an array and another non-array is passed to it';
        $result  = Arr::is([1], 'string');

        $this->assertFalse($result, $message);
    }

    /**
     * Arr::isMultiDimensional should succeed checking if an array contains arrays
     *
     * @covers \Nerd\Arr::isMultiDimensional
     * @depends testArrFixtureIsValid
     */
    public function testArrIsMultiDimensionalSuccess()
    {
        $message = 'Arr::isMultiDimensional cannot read a multi-dimensional array';
        $result  = Arr::isMultiDimensional($this->fixture);

        $this->assertTrue($result);
    }

    /**
     * Arr::isMultiDimensional should fail if not reading a single level array
     *
     * @covers \Nerd\Arr::isMultiDimensional
     * @depends testArrFixtureIsValid
     */
    public function testArrIsMultiDimensionalFail()
    {
        $message = 'Arr::isMultiDimensional falsely reads a non-dimensional array';
        $result  = Arr::isMultiDimensional(['test' => 'value']);

        $this->assertFalse($result);
    }

    /**
     * Arr::toEnumerable should create a new enumerable object
     * 
     * @covers \Nerd\Arr::toEnumerable
     */
    public function testArrToEnum()
    {
        $message  = 'Arr::toEnumerable is unable to convert an array to an enumerable object';
        $result   = Arr::toEnumerable([1, 2, 3]);
        $expected = '\Nerd\Design\Enumerable';

        $this->assertInstanceOf($expected, $result, $message);
    }

    /**
     * Arr::toObject should create an object when an associative array is passed to it
     * 
     * @covers \Nerd\Arr::toObject
     */
    public function testArrToObjectSucceed()
    {
        $message = 'Arr::toObject is unable to convert an array to an object';
        $result  = Arr::toObject(['test' => 'one']);

        $this->assertObject($result, $message);
    }

    /**
     * Arr::toObject should fail when a non-associative array is passed to it
     * 
     * @covers \Nerd\Arr::toObject
     */
    public function testArrToObjectFail()
    {
        $message = 'Arr::toObject is able to convert a non-associative array to an object, it shouldnt';
        $result  = Arr::toObject([1,2,3]);

        $this->assertNotObject($result, $message);
    }
}
