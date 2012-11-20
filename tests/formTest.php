<?php

namespace Nerd;

class FormTest extends TestCase
{
    public function setUp()
    {
        $this->setUpReflection('\\Nerd\\Form');
    }

    /**
     * @covers \Nerd\Form
     */
    public function testFormInNerdNamespace()
    {
       $this->assertEquals($this->ref->getNamespaceName(), 'Nerd');
    }
}
