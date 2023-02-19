<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Filesystem
 */


namespace Aimeos\Base\Filesystem\Manager;


/**
 * Standard file system manager
 *
 * @package Base
 * @subpackage Filesystem
 */
class Standard implements Iface
{
	private array $config;
	private array $objects = [];


	/**
	 * Initializes the object
	 *
	 * @param array $config Associative multi-dimensional configuration
	 */
	public function __construct( array $config )
	{
		$this->config = $config;
	}


	/**
	 * Cleans up the object
	 */
	public function __destruct()
	{
		foreach( $this->objects as $key => $object ) {
			unset( $this->objects[$key] );
		}
	}


	/**
	 * Clean up the objects inside
	 */
	public function __sleep()
	{
		$this->__destruct();
		$this->objects = [];

		return get_object_vars( $this );
	}


	/**
	 * Returns the file system for the given name
	 *
	 * @param string $name Key for the file system
	 * @return \Aimeos\Base\Filesystem\Iface File system object
	 * @throws \Aimeos\Base\Filesystem\Exception If an no configuration for that name is found
	 */
	public function get( string $name ) : \Aimeos\Base\Filesystem\Iface
	{
		if( !isset( $this->objects[$name] ) ) {
			$this->objects[$name] = $this->create( $this->config( $name ) );
		}

		return $this->objects[$name];
	}


	/**
	 * Returns the configuration for the given name
	 *
	 * @param string $name Name of the resource, e.g. "fs" or "fs-media"
	 * @return array|string Configuration values or alias name
	 * @throws \Aimeos\Base\Filesystem\Exception If an no configuration for that name is found
	 */
	protected function config( string $name )
	{
		foreach( [$name, 'fs'] as $fsname )
		{
			if( isset( $this->config[$fsname] ) ) {
				return $this->config[$fsname];
			}
		}

		$msg = sprintf( 'No resource configuration for "%1$s" available', $name );
		throw new \Aimeos\Base\Filesystem\Exception( $msg );
	}


	/**
	 * Creates and returns a new file system object
	 *
	 * @param array $config Resource configuration
	 * @return \Aimeos\Base\Filesystem\Iface File system object
	 * @throws \Aimeos\Base\Filesystem\Exception if file system class isn't found
	 */
	protected function create( array $config ) : \Aimeos\Base\Filesystem\Iface
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
