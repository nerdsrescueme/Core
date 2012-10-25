<?php

namespace Nerd\Source\Traits;

trait Docblock
{
    /**
     * Methods docblock instance
     *
     * @var Nerd\Source\Docblock
     */
    protected $docblock;

    /**
     * Get a (@see Nerd\Source\Docblock) object for this method
     *
     * @return Nerd\Source\Docblock
     */
    public function getDocblock()
    {
        if ($this->docblock === null) {
            $this->docblock = new \Nerd\Source\Docblock($this);
        }

        return $this->docblock;
    }
}
