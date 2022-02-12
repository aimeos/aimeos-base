<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2022
 * @package Base
 * @subpackage Filesystem
 */


namespace Aimeos\Base\Filesystem;


/**
 * Creates a new file system object
 *
 * @package Base
 * @subpackage Filesystem
 */
class Factory
{
	/**
	 * Creates and returns a new file system object
	 *
	 * @param array $config Resource configuration
	 * @return \Aimeos\Base\Filesystem\Iface File system object
	 * @throws \Aimeos\Base\Filesystem\Exception if file system class isn't found
	 */
	public static function create( array $config )
	{
		if( !isset( $config['adapter'] ) ) {
			throw new \Aimeos\Base\Filesystem\Exception( 'File system not configured' );
		}

		$classname = '\Aimeos\Base\Filesystem\\' . ucfirst( (string) $config['adapter'] );

		if( !class_exists( $classname ) ) {
			throw new \Aimeos\Base\Filesystem\Exception( sprintf( 'File system "%1$s" not found', $config['adapter'] ) );
		}

		return new $classname( $config );
	}
}
