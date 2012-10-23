<?php

require_once __DIR__.'/../traits/renderable.php';
require_once __DIR__.'/../traits/attributable.php';
require_once __DIR__.'/../traits/wrappable.php';

abstract class Form_Field extends \PHPUnit_Framework_TestCase
{
	use Renderable
	  , Attributable
	  , Wrappable;

	protected $field;
    protected $ref;
	protected $ins;
	protected $str;

    public function setUp()
    {
        $this->ref = new \ReflectionClass('\\Nerd\\Form\\Field\\'.ucfirst($this->field));
		$this->ins = $this->ref->newInstance();
		$this->str = (string) $this->ins;
    }

    public function testFieldInNerdFormFieldNamespace()
    {
       $this->assertEquals($this->ref->getNamespaceName(), 'Nerd\\Form\\Field');
    }

	public function testFieldIsInstantiable()
	{
		$this->assertTrue($this->ref->isInstantiable());
	}

	public function testFieldExtendsField()
	{
		$parent = $this->ref;
		$found  = false;

		while ($parent = $parent->getParentClass()) {
			if ($parent->getName() == 'Nerd\\Form\\Field') {
				$found = true;
			}
		}

		$this->assertTrue($found);
	}
}
