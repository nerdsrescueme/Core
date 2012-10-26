<?php

/**
 * Asset driver namespace. this namespace controls the driver specification for
 * asset drivers. Multiple driver instances for asset management can be loaded at the
 * same time.
 *
 * @package Nerd
 * @subpackage Asset
 */
namespace Nerd\Asset\Driver;

/**
 * Cascading Style Sheet Asset Class
 *
 * @package Nerd
 * @subpackage Asset
 */
class Css extends \Nerd\Asset\Driver
{
    public $folder = 'css';

    public function compress()
    {
        parent::compress();
        $this->contents = str_replace(array("\n","   "), ' ', $this->contents);

        return $this;
    }

    public function tag()
    {
		$file = str_replace(DS, '/', $this->file);
        return "<link rel=\"stylesheet\" media=\"all\" href=\"{$file}\">";
    }
}
