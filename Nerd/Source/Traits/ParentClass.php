<?php

namespace Nerd\Source\Traits;

trait ParentClass
{
    /**
     * Get this property's (@see Nerd\Source\Klass) object
     *
     * @return Nerd\Source\Klass
     */
    public function getDeclaringClass()
    {
        return new \Nerd\Source\Klass(parent::getDeclaringClass()->getName());
    }
}
