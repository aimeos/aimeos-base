<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Block;

use \Aimeos\Base\View\Exception;


/**
 * View helper class for handling template blocks
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Block\Iface
{
	private array $blocks = [];
	private array $stack = [];


	/**
	 * Returns the block helper
	 *
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object
	 */
	public function transform() : Iface
	{
		return $this;
	}


	/**
	 * Returns the content block for the given name
	 *
	 * @param string $name Name of the block
	 * @return string|null Content of the block
	 */
	public function get( string $name ) : ?string
	{
		if( isset( $this->blocks[$name] ) ) {
			return $this->blocks[$name];
		}

		return null;
	}


	/**
	 * Sets the content of a block for the given name
	 *
	 * @param string $name Name of the block
	 * @param string $content Block content
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object for fluent interface
	 */
	public function set( string $name, string $content ) : Iface
	{
		$this->blocks[$name] = $content;
		return $this;
	}


	/**
	 * Starts a new content block
	 *
	 * @param string $name Name of the block
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object for fluent interface
	 */
	public function start( string $name ) : Iface
	{
		if( in_array( $name, $this->stack ) ) {
			throw new Exception( sprintf( 'Block "%1$s" has already been started', $name ) );
		}

		$this->stack[] = $name;
		ob_start();

		return $this;
	}


	/**
	 * Stores the current content block
	 *
	 * @return \Aimeos\Base\View\Helper\Block\Iface Block object for fluent interface
	 */
	public function stop() : Iface
	{
		if( ( $name = array_pop( $this->stack ) ) === null ) {
			throw new Exception( sprintf( 'No block has been started before' ) );
		}

		$this->blocks[$name] = ob_get_clean();
		return $this;
	}
}
