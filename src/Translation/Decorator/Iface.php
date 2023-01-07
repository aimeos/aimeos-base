<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Translation
 */


namespace Aimeos\Base\Translation\Decorator;


/**
 * Decorator interface for translation classes
 *
 * @package Base
 * @subpackage Translation
 */
interface Iface extends \Aimeos\Base\Translation\Iface
{
	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Translation\Iface $object Translation object or decorator
	 * @return null
	 */
	public function __construct( \Aimeos\Base\Translation\Iface $object );
}
