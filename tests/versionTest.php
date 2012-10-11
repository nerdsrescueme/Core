<?php

namespace Nerd;

class VersionTest extends \PHPUnit_Framework_TestCase
{
	protected $ref;

	public function setUp()
	{
		$this->ref = new \ReflectionClass('\\Nerd\\Version');
	}

	/**
     * @covers \Nerd\Version
     */
    public function testVersionInNerdNamespace()
    {
	   $this->assertEquals($this->ref->getNamespaceName(), 'Nerd');
    }

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionIsUninstantiable()
	{
		$this->assertFalse($this->ref->hasMethod('__construct'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionHasNoMethods()
	{
		$this->assertEmpty($this->ref->getMethods());
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionSimpleExists()
	{
		$this->assertTrue($this->ref->hasConstant('SIMPLE'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionMajorExists()
	{
		$this->assertTrue($this->ref->hasConstant('MAJOR'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionMinorExists()
	{
		$this->assertTrue($this->ref->hasConstant('MINOR'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionReleaseExists()
	{
		$this->assertTrue($this->ref->hasConstant('RELEASE'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionIdExists()
	{
		$this->assertTrue($this->ref->hasConstant('ID'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionPreviewExists()
	{
		$this->assertTrue($this->ref->hasConstant('PREVIEW'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionPreviewTypeExists()
	{
		$this->assertTrue($this->ref->hasConstant('PREVIEW_TYPE'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionPreviewNumberExists()
	{
		$this->assertTrue($this->ref->hasConstant('PREVIEW_NUMBER'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionFullExists()
	{
		$this->assertTrue($this->ref->hasConstant('FULL'));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionSimpleType()
	{
		$this->assertTrue(is_string(Version::SIMPLE));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionMajorType()
	{
		$this->assertTrue(is_int(Version::MAJOR));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionMinorType()
	{
		$this->assertTrue(is_int(Version::MINOR));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionReleaseType()
	{
		$this->assertTrue(is_int(Version::RELEASE));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionIdType()
	{
		$this->assertTrue(is_int(Version::ID));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionPreviewType()
	{
		$this->assertTrue(is_bool(Version::PREVIEW));
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionPreviewTypeOnlyIfPreviewIsTrue()
	{
		if (Version::PREVIEW)
		{
			$this->assertTrue(is_string(Version::PREVIEW_TYPE));
			$this->assertTrue(strlen(Version::PREVIEW_TYPE) > 0);
		}
		else
		{
			$this->assertNull(Version::PREVIEW_TYPE);
		}
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionPreviewNumberOnlyIfPreviewIsTrue()
	{
		if (Version::PREVIEW)
		{
			$this->assertTrue(is_int(Version::PREVIEW_NUMBER) or is_null(Version::PREVIEW_NUMBER));
		}

		// Blank assertion to avoid incomplete test.
		$this->assertTrue(true);
	}

	/**
	 * @covers \Nerd\Version
	 */
	public function testVersionFullFollowsCorrectFormat()
	{
		$compiled = trim(Version::SIMPLE.' '.Version::PREVIEW_TYPE.' '.Version::PREVIEW_NUMBER);
		$this->assertEquals(Version::FULL, $compiled);
	}
}
