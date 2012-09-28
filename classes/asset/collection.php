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

/**
 * Asset Collection Class
 *
 * This class is essentially a wrapper for an enumerable array containing various
 * asset files. It adds the ability to traverse through your loaded assets within
 * definable containers.
 *
 * ## Usage
 *
 *     // Construct via the static asset class builder.
 *     $jsAssets = Asset::collection(array('one.js', 'two.js'));
 *     $jsAssets->find(function(asset)
 *     {
 *         return $asset->path = 'two.js';
 *     });
 *
 *     // Construct via the new operator
 *     $js_assets = new Asset\Collection(array('one.js', 'two.js'));
 *
 * @see Nerd\Design\Collection for a list of available traversal methods
 * 
 * @package Nerd
 * @subpackage Asset
 */
class Collection extends \Nerd\Design\Collection
{
	// Traits
	use \Nerd\Design\Eventable
	  , \Nerd\Design\Renderable;

	/**
	 * Enumerable container
	 *
	 * @var Nerd\Design\Collection
	 */
	public $assets;

	/**
	 * Rendered content of this collection
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Should we compress the data on output?
	 *
	 * @var boolean
	 */
	public $compress = false;

	/**
	 * Should we return the rendered content as HTML tags?
	 *
	 * @var boolean
	 */
	public $tags = true;

	/**
	 * Instance constructor
	 *
	 * Simply provides a reference link from the enumerable property to a more aptly
	 * named one, assets.
	 *
	 * @param    array    Array of assets to import into the collection
	 * @return   Nerd\Asset\Collection
	 */
	public function __construct(array $assets = [])
	{
		parent::__construct($assets);

		$this->triggerEvent('asset.collect', array($this));
		$this->assets = &$this->enumerable;
	}

	public function render()
	{
		if ($this->tags)
		{
			$out = '';

			$this->each(function($asset) use (&$out)
			{
				$out .= $asset->tag().PHP_EOL;
			});

			return $out;
		}

		$this->triggerEvent('asset.render', array($this));

		return (string) $this->content;
	}
}