<?php

use \Nerd\Http;

class HttpTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers \Nerd\Http
     */
    public function testHttpProtocolsDefined()
    {
        $this->assertEquals(Http::$statuses[100], 'Continue');
    }
}
