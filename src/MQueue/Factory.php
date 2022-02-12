<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2022
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue;


/**
 * Creates a new message queue object
 *
 * @package Base
 * @subpackage MQueue
 */
class Factory
{
	/**
	 * Creates and returns a new message queue object
	 *
	 * @param array $config Resource configuration
	 * @return \Aimeos\Base\MQueue\Iface Message queue object
	 * @throws \Aimeos\Base\MQueue\Exception if message queue class isn't found
	 */
	public static function create( array $config )
	{
		if( !isset( $config['adapter'] ) ) {
			throw new \Aimeos\Base\MQueue\Exception( 'Message queue not configured' );
		}

		$classname = '\Aimeos\Base\MQueue\\' . ucfirst( (string) $config['adapter'] );

		if( !class_exists( $classname ) ) {
			throw new \Aimeos\Base\MQueue\Exception( sprintf( 'Message queue "%1$s" not found', $config['adapter'] ) );
		}

		return new $classname( $config );
	}
}
