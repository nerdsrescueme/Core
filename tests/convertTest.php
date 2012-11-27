<?php

namespace Nerd;

class ConvertTest extends TestCase
{
    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Convert');
    }

    /**
     * The Convert class should live in the Nerd namespace
     * 
     * @covers \Nerd\Convert
     */
    public function testConvertInNerdNamespace()
    {
        $message  = 'Convert class is not declared in the Nerd namespace';
        $result   = $this->ref->getNamespaceName();
        $expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Convert
     */
    public function testConvertAllMethodsStatic()
    {
        $message = 'Convert class does not contain any methods';
        $methods = $this->ref->getMethods();

        $this->assertNotEmpty($methods, $message);

        foreach ($methods as $method) {
            $message = 'Convert::'.$method->getName().' is not a static method';
            $result  = $method->isStatic();

            $this->assertTrue($result, $message);
        }
    }

    /**
     * Convert::percentToDecimal should return a decimal value
     *
     * @covers \Nerd\Convert::percentToDecimal
     */
    public function testConvertPercentToDecimal()
    {
        $message  = 'Convert::percentToDecimal does not properly convert values';
        $result   = Convert::percentToDecimal('22.5%');
        $expected = 0.225;

        $this->assertFloat($result, $message);
        $this->assertEquals($result, $expected, $message);

        $result   = Convert::percentToDecimal('110.2%');
        $expected = 1.102;

        $this->assertFloat($result, $message);
        $this->assertEquals($result, $expected, $message);

        $result   = Convert::percentToDecimal('0.2%');
        $expected = 0.002;

        $this->assertFloat($result, $message);
        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Convert::decimalToPercent should return a percent value
     *
     * @covers \Nerd\Convert::decimalToPercent
     */
    public function testConvertDecimalToPercent()
    {
        $message  = 'Convert::decimalToPercent does not properly convert values';
        $result   = Convert::decimalToPercent(0.225);
        $expected = '22.5%';

        $this->assertString($result, $message);
        $this->assertEquals($result, $expected, $message);

        $result   = Convert::decimalToPercent(1.102);
        $expected = '110.2%';

        $this->assertString($result, $message);
        $this->assertEquals($result, $expected, $message);

        $result   = Convert::decimalToPercent(2);
        $expected = '200%';

        $this->assertString($result, $message);
        $this->assertEquals($result, $expected, $message);
    }
}
