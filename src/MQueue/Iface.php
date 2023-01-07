<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue;


/**
 * Common interface for all message queue implementations
 *
 * @package Base
 * @subpackage MQueue
 */
interface Iface
{
	/**
	 * Initializes the message queue object
	 *
	 * @param array $config Associative list of configuration key/value pairs
	 * @return null
	 */
	public function __construct( array $config );

	/**
	 * Returns the queue for the given name
	 *
	 * @param string $name Queue name
	 * @return \Aimeos\Base\MQueue\Queue\Iface Message queue
	 */
	public function getQueue( string $name ) : \Aimeos\Base\MQueue\Queue\Iface;
}
