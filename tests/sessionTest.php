<?php

namespace Nerd;

class SessionTest extends TestCase
{
    public function setUp()
    {
        // Use the session void handler...
        Config::set('session.handler', '\\Nerd\\Session\\Handler\\Void');

        $this->setUpReflection('\\Nerd\\Session');
        $this->ins = Session::instance();

        $this->ins->inject([
            'key' => 'value'
        ]);
    }

    /**
     * Class should live in the Nerd namespace
     * 
     * @covers \Nerd\Session
     */
    public function testSessionInNerdNamespace()
    {
        $message  = 'Session class is not declared in the Nerd namespace';
        $result   = $this->ref->getNamespaceName();
        $expected = 'Nerd';

        $this->assertEquals($result, $expected, $message);
    }

    /**
     * Clear should empty all session data
     *
     * @covers \Nerd\Session::clear
     */
    public function testSessionClear()
    {
        $message = 'Session::clear should remove all data from the session array';

        $this->ins->clear();
        $this->assertEmpty($this->ins->data, $message);
    }
}
