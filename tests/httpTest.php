<?php

namespace Nerd;

class HttpTest extends \PHPUnit_Framework_TestCase
{
	protected $ref;

	public function setUp()
	{
		$this->ref = new \ReflectionClass('\\Nerd\\Http');
	}

	/**
	 * @covers \Nerd\Http
	 */
	public function testHttpIsUninstantiable()
	{
		$this->assertFalse($this->ref->hasMethod('__construct'));
	}

	/**
	 * @covers \Nerd\Http
	 */
	public function testHttpStatusIsArray()
	{
		$this->assertTrue(is_array(Http::$statuses));
	}

	/**
     * @covers \Nerd\Http
     * @depends testHttpStatusIsArray
     */
    public function testHttpStatusDefined()
    {
        $this->assertEquals(Http::$statuses[100], 'Continue');
    }

	/**
	 * @covers \Nerd\Http
	 * @depends testHttpStatusIsArray
	 */
	public function testHttpStatusCommonKeysExist()
	{
		// These are what I believe to be the most common HTTP status codes.
		foreach ([200,301,302,304,400,403,404,500] as $code)
		{
			$this->assertArrayHasKey($code, Http::$statuses);
		}
	}
}
