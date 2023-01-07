<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Config
 */


namespace Aimeos\Base\Config\Decorator;


/**
 * Decorator interface for configuration setting classes
 *
 * @package Base
 * @subpackage Config
 */
interface Iface extends \Aimeos\Base\Config\Iface
{
	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Config\Iface $object Config object or decorator
	 * @return null
	 */
	public function __construct( \Aimeos\Base\Config\Iface $object );
}
