<?php

namespace Nerd;

class FileTest extends TestCase
{
    /**
     * Holds name of temporary test file
     */
    private $file;

    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\File');
        $this->file = sys_get_temp_dir() . DS . 'nerd.tmp';
    }

    /**
     * The File class should live in the Nerd namespace
     * 
     * @covers \Nerd\File
     */
    public function testFileInNerdNamespace()
    {
        $message  = 'File class is not declared in the Nerd namespace';
        $result   = $this->ref->getNamespaceName();
        $expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * The File class should extend SplFileObject
     *
     * @covers \Nerd\File
     */
    public function testFileExtendsSpl()
    {
        $message  = 'File class does not extend SplFileObject';
        $result   = $this->ref->getParentClass()->getName();
        $expected = 'SplFileObject';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * File::create should be able to create an empty file in the tmp directory
     *
     * @covers \Nerd\File::create
     */
    public function testFileCreateEmpty()
    {
        $message = 'File::create cannot create an empty file';
        $return  = File::create($this->file);
        $result  = file_exists($this->file);

        $this->assertTrue($return, $message);
        $this->assertTrue($result, $message);

        unlink($this->file);
    }

    /**
     * File::create should return boolean when creating an empty file
     *
     * @covers \Nerd\File::create
     * @depends testFileCreateEmpty
     */
    public function testFileCreateEmptyReturnsBoolean()
    {
        $message = 'File::create does not return a boolean value';
        $result  = File::create($this->file);

        $this->assertBoolean($result, $message);

        unlink($this->file);
    }

    /**
     * File::create should be able to create a file in the tmp directory
     *
     * @covers \Nerd\File::create
     * @depends testFileCreateEmpty
     */
    public function testFileCreate()
    {
        $message = 'File::create cannot create a file with content';
        $return  = File::create($this->file, 'This is the content') !== false;
        $result  = file_exists($this->file);

        $this->assertTrue($return, $message);
        $this->assertTrue($result, $message);

        unlink($this->file);
    }

    /**
     * File::create should return boolean when creating a file
     *
     * @covers \Nerd\File::create
     * @depends testFileCreate
     */
    public function testFileCreateReturnsBoolean()
    {
        $message = 'File::create does not return a boolean value';
        $result  = File::create($this->file);

        $this->assertBoolean($result, $message);

        unlink($this->file);
    }

    /**
     * File::create should put content verbatim in to a new file
     *
     * @covers \Nerd\File::create
     * @depends testFileCreate
     */
    public function testFileCreateContentsMatch()
    {
        $content = 'This is the content';
        $message = 'File::create content of new file does not match content given';

        File::create($this->file, $content);

        $result = file_get_contents($this->file);
        $this->assertEquals($result, $content, $message);
        unlink($this->file);
    }

    /**
     * File::append should append content to an existing file
     *
     * @covers \Nerd\File::append
     */
    public function testFileAppend()
    {
        $content = 'Content';
        $message = 'File::append is unable to append data to a file';

        file_put_contents($this->file, $content);

        $result = (File::append($this->file, 'append') !== false);
        $this->assertTrue($result, $message);
        unlink($this->file);
    }

    /**
     * File::append should append content verbatim to a file
     *
     * @covers \Nerd\File::append
     * @depends testFileAppend
     */
    public function testFileAppendContentMatch()
    {
        $content = 'One';
        $append  = 'Two';
        $message = 'File::append content of file does not match expected content';

        file_put_contents($this->file, $content);
        File::append($this->file, $append);

        $result = file_get_contents($this->file);
        $this->assertEquals($result, $content.$append, $message);
        unlink($this->file);
    }

    /**
     * File::delete should delete an existing file
     *
     * @covers \Nerd\File::delete
     */
    public function testFileDelete()
    {
        $fileCreated = touch($this->file);
        File::delete($this->file);

        $message = 'File::delete can not delete an existing file';
        $result  = file_exists($this->file);

        $this->assertTrue($fileCreated, 'Unable to create file for test');
        $this->assertFalse($result, $message);

        $result and unlink($this->file);
    }

    /**
     * File::mime should accurately report the type of file given
     *
     * @covers \Nerd\File::mime
     */
    public function testFileMime()
    {
        $message  = 'File::mime does not accurately report the mime type';
        $result   = File::mime('test.txt');
        $expected = 'text/plain';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * File::mime should populate File::$mimes on first execution
     *
     * @covers \Nerd\File
     * @depends testFileMime
     */
    public function testFileMimePropertyPopulated()
    {
        File::mime('test.txt');

        $message = 'File::mime is unable to populate mimes array';
        $result  = isset(File::$mimes);

        $this->assertTrue($result, $message);
    }

    /**
     * File::mime should return a string value on success
     *
     * @covers \Nerd\File::mime
     * @depends testFileMime
     */
    public function testfileMimeReturnsString()
    {
        $message = 'File::mime does not return a string value on success';
        $result  = File::mime('test.txt');

        $this->assertString($result, $message);
    }

    /**
     * File::mime should return a default mime when provided
     *
     * @covers \Nerd\File::mime
     */
    public function testFileMimeDefault()
    {
        $message  = 'File::mime does not return a default value';
        $result   = File::mime('test.unknowntype', 'text/plain');
        $expected = 'text/plain';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * In order to execute the function mapping, there must be a static caller
     * method declared within the File class
     *
     * @covers \Nerd\File
     */
    public function testFileStaticCallerExists()
    {
        $message = 'File class does not implement a call static method';
        $result  = $this->ref->hasMethod('__callStatic');

        $this->assertTrue($result, $message);
    }

    /**
     * File::exists should be able to determine if a file does or does not exist
     *
     * @covers \Nerd\File::exists
     * @depends testFileStaticCallerExists
     */
    public function testFileExists()
    {
        $message = 'File::exists can not determine if a file exists';
        $result  = File::exists(__FILE__);

        $this->assertTrue($result, $message);

        $message = 'File::exists can not determine if a file does not exist';
        $result2  = File::exists('nonexistent.file');

        $this->assertFalse($result2, $message);

        $message = 'File::exists does not return boolean values every time';

        $this->assertBoolean($result);
        $this->assertBoolean($result2);
    }

    /**
     * File::get should read the contents of an existing file
     *
     * @covers \Nerd\File::get
     * @depends testFileStaticCallerExists
     */
    public function testFileGet()
    {
        $message = 'File::get does not return a string value';
        $result   = File::get(__FILE__);
        $expected = file_get_contents(__FILE__);

        $this->assertString($result, $message);

        $message  = 'File::get can not properly read an existing file';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * File::put should write contents to a file
     *
     * @covers \Nerd\File::put
     * @depends testFileStaticCallerExists
     */
    public function testFilePut()
    {
        // Needs better testing.
        $message  = 'File::put can not write to a file';
        $result   = (File::put($this->file, 'content') !== false);

        $this->assertTrue($result, $message);
        unlink($this->file);
    }

    /**
     * File::type should properly recognize the type of file given
     *
     * @covers \Nerd\File::type
     * @depends testFileStaticCallerExists
     */
    public function testFileType()
    {
        $message  = 'File::type can not properly determine a file';
        $result   = File::type(__FILE__);
        $expected = 'file';

        $this->assertEquals($result, $expected, $message);

        $message  = 'File::type can not properly determine a directory';
        $result   = File::type(__DIR__);
        $expected = 'dir';

        $this->assertEquals($result, $expected, $message);

        // Test fifo, char, block, link, socket and unknown.
    }

    /**
     * File::size should be able to accurately read the size of a file
     *
     * @covers \Nerd\File::size
     * @depends testFileStaticCallerExists
     */
    public function testFileSize()
    {
        file_put_contents($this->file, 'a');

        $message  = 'File::size can not properly read the size of a file';
        $result   = File::size($this->file);
        $expected = 1;

        $this->assertEquals($result, $expected, $message);
        unlink($this->file);
    }

    /**
     * File::modified should be able to accurately read the modification date
     *
     * @covers \Nerd\File::modified
     * @depends testFileStaticCallerExists
     */
    public function testFileModified()
    {
        touch($this->file);

        $message  = 'File::modified does not accurately read the file modification date';
        $result   = File::modified($this->file);
        $expected = range(time()-1, time()+1); // Accounts for timing...

        $this->assertTrue(in_array($result, $expected), $message);
        unlink($this->file);
    }

    /**
     * File::extension should accurately grab the file extension
     *
     * @covers \Nerd\File::extension
     * @depends testFileStaticCallerExists
     */
    public function testFileExtension()
    {
        $message  = 'File::extension does not accurately read a file extension';
        $result   = File::extension(__FILE__);
        $expected = 'php';

        $this->assertEquals($result, $expected, $message);

        $message = 'File::extension does not return a string value';

        $this->assertString($result, $message);
    }

    /**
     * File::touch should be able to create an empty file
     *
     * @covers \Nerd\File::touch
     * @depends testFileStaticCallerExists
     */
    public function testFileTouch()
    {
        $message = 'File::touch does not return a boolean value';
        $return  = File::touch($this->file);
        $result  = file_exists($this->file);

        $this->assertBoolean($return, $message);

        $message = 'File::touch can not create an empty file';

        $this->assertTrue($result, $message);
        unlink($this->file);
    }
}
