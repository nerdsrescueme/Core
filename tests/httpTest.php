<?php

namespace Nerd;

class HttpTest extends TestCase
{
    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Http');
    }

    /**
     * Http class should reside within the Nerd namespace
     * 
     * @covers \Nerd\Http
     */
    public function testHttpInNerdNamespace()
    {
        $message  = 'Http class does not reside within the Nerd namespace';
        $result   = $this->ref->getNamespaceName();
        $expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Http should not contain a constructor
     * 
     * @covers \Nerd\Http
     */
    public function testHttpIsUninstantiable()
    {
        $message = 'Http contains a constructor when it should not';
        $result  = $this->ref->hasMethod('__construct');

        $this->assertFalse($result, $message);
    }

    /**
     * Http should contain a status array
     * 
     * @covers \Nerd\Http
     */
    public function testHttpStatusIsArray()
    {
        $message = 'Http does not contain a status array';

        $this->assertArray(Http::$statuses, $message);
    }

    /**
     * Simply check for some of the common codes to see if they're there
     * 
     * @covers \Nerd\Http
     * @depends testHttpStatusIsArray
     */
    public function testHttpStatusCommonKeysExist()
    {
        $message = 'Http::$statuses does not contain the common code %s';

        // These are what I believe to be the most common HTTP status codes.
        foreach ([200,301,302,304,400,403,404,500] as $code) {
            $this->assertArrayHasKey($code, Http::$statuses, sprintf($message, $code));
        }
    }
}
