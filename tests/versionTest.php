<?php

namespace Nerd;

class VersionTest extends TestCase
{
    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Version');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionInNerdNamespace()
    {
        $message  = 'Version is not declared within the Nerd namespace';
        $result   = $this->ref->getNamespaceName();
        $expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionIsUninstantiable()
    {
        $message = 'Version should not contain a __construct method';
        $result  = $this->ref->hasMethod('__construct');

        $this->assertFalse($result, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionHasNoMethods()
    {
        $message = 'Version should not contain any methods';
        $result  = $this->ref->getMethods();

        $this->assertEmpty($result, $message);
    }

    private function constantExists($constant)
    {
        $message = "Version should declare the constant $constant";
        $result  = $this->ref->hasConstant($constant);

        $this->assertTrue($result);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionSimpleExists()
    {
        $this->constantExists('SIMPLE');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionMajorExists()
    {
        $this->constantExists('MAJOR');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionMinorExists()
    {
       $this->constantExists('MINOR');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionReleaseExists()
    {
        $this->constantExists('RELEASE');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionIdExists()
    {
        $this->constantExists('ID');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionPreviewExists()
    {
        $this->constantExists('PREVIEW');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionPreviewTypeExists()
    {
        $this->constantExists('PREVIEW_TYPE');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionPreviewNumberExists()
    {
        $this->constantExists('PREVIEW_NUMBER');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionFullExists()
    {
        $this->constantExists('FULL');
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionSimpleType()
    {
        $message = 'Version::SIMPLE should be a string';

        $this->assertString(Version::SIMPLE, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionMajorType()
    {
        $message = 'Version::MAJOR should be an integer';

        $this->assertInteger(Version::MAJOR, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionMinorType()
    {
        $message = 'Version::MINOR should be an integer';

        $this->assertInteger(Version::MINOR, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionReleaseType()
    {
        $message = 'Version::RELEASE should be an integer';

        $this->assertInteger(Version::RELEASE, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionIdType()
    {
        $message = 'Version::ID should be an integer';

        $this->assertInteger(Version::ID, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionPreviewType()
    {
        $message = 'Version::PREVIEW should be a boolean value';

        $this->assertBoolean(Version::PREVIEW, $message);
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionPreviewTypeOnlyIfPreviewIsTrue()
    {
        if (Version::PREVIEW) {
            $this->assertString(Version::PREVIEW_TYPE);
            $this->assertTrue(strlen(Version::PREVIEW_TYPE) > 0);
        } else {
            $this->assertNull(Version::PREVIEW_TYPE);
        }
    }

    /**
     * @covers \Nerd\Version
     */
    public function testVersionPreviewNumberOnlyIfPreviewIsTrue()
    {
        if (Version::PREVIEW) {
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
