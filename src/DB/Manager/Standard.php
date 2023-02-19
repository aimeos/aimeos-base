<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Manager;


/**
 * Manager for database connections
 *
 * @package Base
 * @subpackage DB
 */
class Standard implements \Aimeos\Base\DB\Manager\Iface
{
	private array $objects = [];
	private array $config;
	private string $type;


	/**
	 * Initializes the database manager object
	 *
	 * @param array $config Database resource configuration
	 * @param string $type Type of the connection
	 */
	public function __construct( array $config, string $type = 'PDO' )
	{
		$this->config = $config;
		$this->type = $type;
	}


	/**
	 * Cleans up the object
	 */
	public function __destruct()
	{
		foreach( $this->objects as $key => $conn ) {
			unset( $this->objects[$key] );
		}
	}


	/**
	 * Reset when cloning the object
	 */
	public function __clone()
	{
		$this->objects = [];
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
	 * Returns a database connection.
	 *
	 * @param string $name Name of the resource in configuration
	 * @param bool $new Create a new connection instead of returning the existing one
	 * @return \Aimeos\Base\DB\Connection\Iface
	 */
	public function get( string $name = 'db', bool $new = false ) : \Aimeos\Base\DB\Connection\Iface
	{
		if( $new ) {
			return $this->create( $this->config( $name ) );
		}

		if( !isset( $this->objects[$name] ) ) {
			$this->objects[$name] = $this->create( $this->config( $name ) );
		}

		return $this->objects[$name];
	}


	/**
	 * Returns the configuration for the given name
	 *
	 * @param string $name Name of the resource, e.g. "db" or "db-product"
	 * @return array Configuration values
	 * @throws \Aimeos\Base\DB\Exception If an no configuration for that name is found
	 */
	protected function config( string $name ) : array
	{
		foreach( [$name, 'db'] as $fsname )
		{
			if( isset( $this->config[$fsname] ) ) {
				return $this->config[$fsname];
			}
		}

		$msg = sprintf( 'No resource configuration for "%1$s" available', $name );
		throw new \Aimeos\Base\DB\Exception( $msg );
	}


	/**
	 * Creates and returns a database connection.
	 *
	 * @param array $config Database connection configurations
	 * @param string $type Type of the connection
	 * @return \Aimeos\Base\DB\Connection\Iface Instance of a database connection
	 * @throws \Aimeos\Base\DB\Exception if database connection class isn't found
	 */
	protected function create( array $config )
	{
		$classname = '\Aimeos\Base\DB\Connection\\' . $this->type;

		if( !class_exists( $classname ) ) {
			throw new \Aimeos\Base\DB\Exception( sprintf( 'Database connection "%1$s" not found', $this->type ) );
		}

		return new $classname( $config );
	}
}
