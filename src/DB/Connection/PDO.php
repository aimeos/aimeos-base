<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Connection;


/**
 * Database connection class for \PDO connections.
 *
 * @package Base
 * @subpackage DB
 */
class PDO extends Base implements Iface
{
	private ?\PDO $connection = null;
	private int $txnumber = 0;
	private array $stmts = [];


	/**
	 * Initializes the PDO connection object.
	 *
	 * @param array $params Associative list of connection parameters
	 */
	public function __construct( array $params )
	{
		if( !isset( $params['dsn'] ) ) {
			$params['dsn'] = $this->dsn( $params );
		}

		parent::__construct( $params );

		$this->stmts = $params['stmt'] ?? [];
		$this->connect();
	}


	/**
	 * Closes the connection to the database server
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function close() : Iface
	{
		if( $this->inTransaction() ) {
			$this->rollback();
		}

		unset( $this->connection );
		return $this;
	}


	/**
	 * Connects (or reconnects) to the database server
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function connect() : Iface
	{
		$param = $this->getParameters();
		$param['driverOptions'][\PDO::ATTR_CASE] = \PDO::CASE_NATURAL;
		$param['driverOptions'][\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;
		$param['driverOptions'][\PDO::ATTR_ORACLE_NULLS] = \PDO::NULL_NATURAL;
		$param['driverOptions'][\PDO::ATTR_STRINGIFY_FETCHES] = false;

		$pdo = new \PDO( $param['dsn'], $param['username'] ?? '', $param['password'] ?? '', $param['driverOptions'] );
		$conn = $this->connection;

		$this->connection = $pdo;
		$this->txnumber = 0;

		unset( $conn );

		foreach( $this->stmts as $stmt ) {
			$this->create( $stmt )->execute()->finish();
		}

		return $this;
	}


	/**
	 * Creates a \PDO database statement.
	 *
	 * @param string $sql SQL statement, maybe with place holders
	 * @return \Aimeos\Base\DB\Statement\Iface PDO statement object
	 * @throws \Aimeos\Base\DB\Exception if type is invalid or the \PDO object throws an exception
	 */
	public function create( string $sql ) : \Aimeos\Base\DB\Statement\Iface
	{
		try
		{
			if( strpos( $sql, '?' ) === false ) {
				return new \Aimeos\Base\DB\Statement\PDO\Simple( $this, $sql );
			}

			return new \Aimeos\Base\DB\Statement\PDO\Prepared( $this, $sql );
		}
		catch( \PDOException $e )
		{
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode(), $e->errorInfo );
		}
	}


	/**
	 * Returns the underlying connection object
	 *
	 * @return \PDO Underlying connection object
	 */
	public function getRawObject()
	{
		return $this->connection;
	}


	/**
	 * Checks if a transaction is currently running
	 *
	 * @return bool True if transaction is currently running, false if not
	 */
	public function inTransaction() : bool
	{
		return $this->connection->inTransaction();
	}


	/**
	 * Starts a transaction for this connection.
	 *
	 * Transactions can't be nested and a new transaction can only be started
	 * if the previous transaction was committed or rolled back before.
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function begin() : Iface
	{
		if( $this->txnumber === 0 )
		{
			if( $this->connection->beginTransaction() === false ) {
				throw new \Aimeos\Base\DB\Exception( 'Unable to start new transaction' );
			}
		}

		$this->txnumber++;
		return $this;
	}


	/**
	 * Commits the changes done inside of the transaction to the storage.
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function commit() : Iface
	{
		if( $this->txnumber === 1 )
		{
			if( $this->connection->commit() === false ) {
				throw new \Aimeos\Base\DB\Exception( 'Failed to commit transaction' );
			}
		}

		$this->txnumber--;
		return $this;
	}


	/**
	 * Discards the changes done inside of the transaction.
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function rollback() : Iface
	{
		if( $this->txnumber === 1 )
		{
			if( $this->connection->rollBack() === false ) {
				throw new \Aimeos\Base\DB\Exception( 'Failed to roll back transaction' );
			}
		}

		$this->txnumber--;
		return $this;
	}


	/**
	 * Returns the connection DSN
	 *
	 * @param array $params Associative list of connection parameters
	 * @return string Connection DSN
	 */
	protected function dsn( array $params ) : string
	{
		$adapter = $params['adapter'] ?? 'mysql';
		$host = $params['host'] ?? null;
		$port = $params['port'] ?? null;
		$sock = $params['socket'] ?? null;
		$dbase = $params['database'] ?? null;

		$dsn = $adapter . ':';

		if( $adapter === 'sqlsrv' )
		{
			$dsn .= 'Database=' . $dbase;
			$dsn .= isset( $host ) ? ';Server=' . $host . ( isset( $port ) ? ',' . $port : '' ) : '';
		}
		elseif( $sock == null )
		{
			$dsn .= 'dbname=' . $dbase;
			$dsn .= isset( $host ) ? ';host=' . $host : '';
			$dsn .= isset( $port ) ? ';port=' . $port : '';
		}
		else
		{
			$dsn .= 'dbname=' . $dbase . ';unix_socket=' . $sock;
		}

		return $dsn;
	}
}
