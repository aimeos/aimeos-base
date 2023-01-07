<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 * @package Base
 * @subpackage Process
 */


namespace Aimeos\Base\Process\Decorator;


/**
 * Common interface for parallel processing decorators
 *
 * @package Base
 * @subpackage Process
 */
interface Iface extends \Aimeos\Base\Process\Iface
{
	/**
	 * Initializes the object
	 *
	 * @param \Aimeos\Base\Process\Iface $object Parallel processing object
	 * @return void
	 */
	public function __construct( \Aimeos\Base\Process\Iface $object );
}
