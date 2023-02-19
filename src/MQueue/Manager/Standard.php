<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue\Manager;


/**
 * Standard message queue manager
 *
 * @package Base
 * @subpackage MQueue
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
	 * Clones the objects inside.
	 */
	public function __clone()
	{
		$this->objects[] = [];
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
	 * Returns the message queue for the given name
	 *
	 * @param string $name Resource name of the message queue
	 * @return \Aimeos\Base\MQueue\Iface Message queue object
	 * @throws \Aimeos\Base\MQueue\Exception If an no configuration for that name is found
	 */
	public function get( string $name ) : \Aimeos\Base\MQueue\Iface
	{
		if( !isset( $this->objects[$name] ) ) {
			$this->objects[$name] = $this->create( $this->config( $name ) );
		}

		return $this->objects[$name];
	}


	/**
	 * Returns the configuration for the given name
	 *
	 * @param string $name Name of the resource, e.g. "mq" or "mq-email"
	 * @return array Configuration values
	 * @throws \Aimeos\Base\MQueue\Exception If an no configuration for that name is found
	 */
	protected function config( string $name ) : array
	{
		foreach( [$name, 'mq'] as $mqname )
		{
			if( isset( $this->config[$mqname] ) ) {
				return $this->config[$mqname];
			}
		}

		$msg = sprintf( 'No resource configuration for "%1$s" available', $name );
		throw new \Aimeos\Base\MQueue\Exception( $msg );
	}


	/**
	 * Creates and returns a new message queue object
	 *
	 * @param array $config Resource configuration
	 * @return \Aimeos\Base\MQueue\Iface Message queue object
	 * @throws \Aimeos\Base\MQueue\Exception if message queue class isn't found
	 */
	protected function create( array $config )
	{
		if( !isset( $config['adapter'] ) ) {
			throw new \Aimeos\Base\MQueue\Exception( 'Message queue not configured' );
		}

		$classname = '\Aimeos\Base\MQueue\\' . ucfirst( (string) $config['adapter'] );

		if( !class_exists( $classname ) ) {
			throw new \Aimeos\Base\MQueue\Exception( sprintf( 'Message queue "%1$s" not found', $config['adapter'] ) );
		}

		$config['db'] = $this->config[$config['db'] ?? 'db'] ?? [];

		return new $classname( $config );
	}
}
