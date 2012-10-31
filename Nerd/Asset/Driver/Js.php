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
 * Javascript Asset Class
 *
 * @package Nerd
 * @subpackage Asset
 */
class Js extends \Nerd\Asset\Driver
{
    public $folder = 'js';

    public function compress()
    {
        parent::compress();
        $this->contents .= '/** COMPRESSED **/';

        return $this;
    }

    public function tag()
    {
        $file = str_replace(DS, '/', $this->file);
        return "<script src=\"{$file}\" type=\"text/javascript\"></script>";
    }
}
