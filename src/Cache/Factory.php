<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Cache
 */


namespace Aimeos\Base\Cache;


/**
 * Creates new instances of classes in the cache domain.
 *
 * @package Base
 * @subpackage Cache
 */
class Factory
{
	/**
	 * Creates and returns a cache object.
	 *
	 * @param string $name Object type name
	 * @param array $args Variable list of arguments passed to the cache object constructor
	 * @return \Aimeos\Base\Cache\Iface Cache object of the requested type
	 * @throws \Aimeos\Base\Cache\Exception if class isn't found
	 */
	public static function create( $name, ...$args ) : \Aimeos\Base\Cache\Iface
	{
		if( ctype_alnum( $name ) === false )
		{
			$classname = is_string( $name ) ? '\Aimeos\Base\Cache\\' . $name : '<not a string>';
			throw new \Aimeos\Base\Cache\Exception( sprintf( 'Invalid characters in class name "%1$s"', $classname ) );
		}

		$iface = \Aimeos\Base\Cache\Iface::class;
		$classname = '\Aimeos\Base\Cache\\' . ucwords( $name );

		if( class_exists( $classname ) === false ) {
			throw new \Aimeos\Base\Cache\Exception( sprintf( 'Class "%1$s" not available', $classname ) );
		}

		$object = new $classname( ...$args );

		if( !( $object instanceof $iface ) ) {
			throw new \Aimeos\Base\Cache\Exception( sprintf( 'Class "%1$s" does not implement interface "%2$s"', $classname, $iface ) );
		}

		return $object;
	}
}
