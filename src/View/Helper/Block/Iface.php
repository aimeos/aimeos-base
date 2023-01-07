<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Block;


/**
 * View helper class for handling template blocks
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the block helper
	 *
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object
	 */
	public function transform() : Iface;

	/**
	 * Returns the content block for the given name
	 *
	 * @param string $name Name of the block
	 * @return string|null Content of the block
	 */
	public function get( string $name ) : ?string;

	/**
	 * Sets the content of a block for the given name
	 *
	 * @param string $name Name of the block
	 * @param string $content Block content
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object for fluent interface
	 */
	public function set( string $name, string $content ) : Iface;

	/**
	 * Starts a new content block
	 *
	 * @param string $name Name of the block
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object for fluent interface
	 */
	public function start( string $name ) : Iface;

	/**
	 * Stores the current content block
	 *
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object for fluent interface
	 */
	public function stop() : Iface;
}
