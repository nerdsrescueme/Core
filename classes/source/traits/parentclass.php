<?php

namespace Nerd\Source\Traits;

trait Parentclass
{
	/**
	 * Get this property's (@see Nerd\Source\Klass) object
	 *
	 * @return Nerd\Source\Klass
	 */
	public function getDeclaringClass()
	{
		return new Klass(parent::getDeclaringClass()->getName());
	}
}