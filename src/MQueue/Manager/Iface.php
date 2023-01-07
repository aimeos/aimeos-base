<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue\Manager;


/**
 * Interface for message queue managers
 *
 * @package Base
 * @subpackage MQueue
 */
interface Iface
{
	/**
	 * Returns the message queue for the given name
	 *
	 * @param string $resource Resource name of the message queue
	 * @return \Aimeos\Base\MQueue\Iface Message queue object
	 * @throws \Aimeos\Base\MQueue\Exception If an error occurs
	 */
	public function get( string $resource ) : \Aimeos\Base\MQueue\Iface;
}
