<?php

/**
 * Asset driver namespace. this namespace controls the driver specification for
 * asset drivers. Multiple driver instances for asset management can be loaded at the
 * same time.
 *
 * @package Nerd
 * @subpackage Asset
 */
namespace Nerd\Asset;

// Aliasing rules
use \Nerd\Config
  , \Nerd\Url;

/**
 * Asset driver interface
 *
 * This abstract class defines the driver structure of which functions a driver must
 * implement and how they should be called.
 *
 * @package Nerd
 * @subpackage Asset
 */
abstract class Driver
{
    // Traits
    use \Nerd\Design\Renderable;

    /**
     * Path to the asset file
     *
     * @var    Nerd\Uri\Driver
     */
    public $uri;

    /**
     * Full path to the asset file
     *
     * @var    string
     */
    public $fullPath;

    /**
     * The rendered contents of the asset file
     *
     * @var    string
     */
    protected $contents;

    /**
     * Instance Constructor
     *
     * @param    string                  Path to file relative to DOCROOT
     * @throws OutOfBoundsException When the asset cannot be located
     * @return Nerd\Asset\Type
     */
    public function __construct($file, $folder = '')
    {
        if (!empty($folder)) {
            $file = trim($folder, '/').DS.$file;
        }

        $this->file = Url::asset($file);
        $this->fullPath = join(DS, [\Nerd\DOCROOT, trim($file, '/')]);

        if (!file_exists($this->fullPath)) {
            throw new \OutOfBoundsException("The asset [$file] could not be found.");
        }
    }

    /**
     * Default compression method (no compression)
     *
     * Asset files are routinely compressed to save bandwidth and increase page load
     * times. This method allows us to programatically compress the contents of our
     * asset files depending on which driver we specify.
     *
     * @return Nerd\Asset\Type
     */
    public function compress()
    {
        $this->contents = (string) $this;

        return $this;
    }

    /**
     * Get the contents of this asset file
     *
     * @throws \RuntimeException When an asset file cannot be read
     * @return string            Contents of asset file
     */
    public function render()
    {
        if ($this->contents === null) {
            try {
                $this->contents = file_get_contents($this->fullPath);
            } catch (\Exception $e) {
                throw new \RuntimeException("Unable to read the asset file ['{$this->path}'] ensure you have proper permission to read this file.");
            }
        }

        return $this->contents;
    }

    /**
     * Tag function
     *
     * Each sub-class must be able to render itself as an HTML tag.
     *
     * @return string HTML tag
     */
    abstract public function tag();
}
