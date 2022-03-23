<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2022
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Manager;


/**
 * Manager for database connections using the DBAL library
 *
 * @package Base
 * @subpackage DB
 */
class DBAL implements \Aimeos\Base\DB\Manager\Iface
{
	private $connections = [];
	private $conns = [];
	private $count = [];
	private $config;


	/**
	 * Initializes the database manager object
	 *
	 * @param array $config Database resource configuration
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
		foreach( $this->connections as $name => $list )
		{
			foreach( $list as $key => $conn ) {
				unset( $this->connections[$name][$key] );
			}
		}

		foreach( $this->conns as $key => $conn ) {
			unset( $this->conns[$key] );
		}
	}


	/**
	 * Reset when cloning the object
	 */
	public function __clone()
	{
		$this->connections = [];
		$this->conns = [];
		$this->count = [];
	}


	/**
	 * Clean up the objects inside
	 */
	public function __sleep()
	{
		$this->__destruct();

		$this->connections = [];
		$this->conns = [];
		$this->count = [];

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
		$name = isset( $this->config[$name] ) ? $name : 'db';

		if( $new ) {
			return $this->create( $name );
		}

		if( !isset( $this->conns[$name] ) ) {
			$this->conns[$name] = $this->create( $name );
		}

		return $this->conns[$name];
	}


	/**
	 * Returns a database connection.
	 *
	 * @param string $name Name of the resource in configuration
	 * @return \Aimeos\Base\DB\Connection\Iface
	 */
	public function acquire( string $name = 'db' )
	{
		try
		{
			$name = isset( $this->config[$name] ) ? $name : 'db';

			if( !isset( $this->config[$name] ) ) {
				throw new \Aimeos\Base\DB\Exception( "No database configuration for resource \"$name\" available" );
			}

			if( !isset( $this->connections[$name] ) || empty( $this->connections[$name] ) )
			{
				if( !isset( $this->count[$name] ) ) {
					$this->count[$name] = 0;
				}

				$limit = $this->config[$name]['limit'] ?? -1;

				if( $limit >= 0 && $this->count[$name] >= $limit )
				{
					$msg = "Maximum number of connections ($limit) for \"$name\" exceeded";
					throw new \Aimeos\Base\DB\Exception( $msg );
				}

				$this->connections[$name] = array( $this->create( $name ) );
				$this->count[$name]++;
			}

			return array_pop( $this->connections[$name] );
		}
		catch( \Exception $e ) {
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode() );
		}
	}


	/**
	 * Releases the connection for reuse
	 *
	 * @param \Aimeos\Base\DB\Connection\Iface $connection Connection object
	 * @param string $name Name of resource
	 */
	public function release( \Aimeos\Base\DB\Connection\Iface $connection, string $name = 'db' )
	{
		if( ( $connection instanceof \Aimeos\Base\DB\Connection\DBAL ) === false ) {
			throw new \Aimeos\Base\DB\Exception( 'Connection object isn\'t of type DBAL' );
		}

		$name = isset( $this->config[$name] ) ? $name : 'db';

		$this->connections[$name][] = $connection;
	}


	/**
	 * Creates a new database connection.
	 *
	 * @param string $name Name to the database configuration in the resource file
	 * @return \Aimeos\Base\DB\Connection\Iface Database connection
	 */
	protected function create( string $name ) : \Aimeos\Base\DB\Connection\Iface
	{
		$params = $this->config[$name] ?? [];
		$adapter = $params['adapter'] ?? 'mysql';

		$params['user'] = $params['username'] ?? null;
		$params['dbname'] = $params['database'] ?? null;

		if( $socket = $params['socket'] ?? null ) {
			$params['unix_socket'] = $socket;
		}

		switch( $adapter )
		{
			case 'mysql': $params['driver'] = 'pdo_mysql'; break;
			case 'oracle': $params['driver'] = 'pdo_oci'; break;
			case 'pgsql': $params['driver'] = 'pdo_pgsql'; break;
			case 'sqlite': $params['driver'] = 'pdo_sqlite'; break;
			case 'sqlsrv': $params['driver'] = 'pdo_sqlsrv'; break;
			default: $params['driver'] = $adapter;
		}

		return new \Aimeos\Base\DB\Connection\DBAL( $params, $params['stmt'] ?? [] );
	}
}
