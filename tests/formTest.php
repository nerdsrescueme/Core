<?php

namespace Nerd;

class FormTest extends \PHPUnit_Framework_TestCase
{
    protected $ref;

    public function setUp()
    {
        $this->ref = new \ReflectionClass('\\Nerd\\Form');
    }

    /**
     * @covers \Nerd\Form
     */
    public function testFormInNerdNamespace()
    {
       $this->assertEquals($this->ref->getNamespaceName(), 'Nerd');
    }
}
