<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Connection;


/**
 * Required functions for database connection objects.
 *
 * @package Base
 * @subpackage DB
 */
interface Iface
{
	/**
	 * Initializes the DBAL connection object
	 *
	 * @param array $params Associative list of connection parameters
	 */
	public function __construct( array $params );

	/**
	 * Closes the connection to the database server
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function close() : Iface;

	/**
	 * Connects (or reconnects) to the database server
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function connect() : Iface;

	/**
	 * Creates a database statement.
	 *
	 * @param string $sql SQL statement, maybe with place holders
	 * @return \Aimeos\Base\DB\Statement\Iface
	 */
	public function create( string $sql ) : \Aimeos\Base\DB\Statement\Iface;

	/**
	 * Escapes the value if necessary for direct inclusion in SQL statement.
	 *
	 * @param string|null $data Value to escape or null for no value
	 * @return string Escaped string
	 */
	public function escape( string $data = null ) : string;

	/**
	 * Returns a quoted identifier for the passed name
	 *
	 * @param string $name Identifier name
	 * @return string Quoted identifier
	 * @throws \Aimeos\Base\DB\Exception If identifier name already contains a quote character
	 */
	public function qi( string $name ) : string;

	/**
	 * Returns the underlying connection object
	 *
	 * @return mixed Underlying connection object
	 */
	public function getRawObject();

	/**
	 * Checks if a transaction is currently running
	 *
	 * @return bool True if transaction is currently running, false if not
	 */
	public function inTransaction() : bool;

	/**
	 * Starts a transaction for this connection.
	 *
	 * Transactions can't be nested and a new transaction can only be started
	 * if the previous transaction was committed or rolled back before.
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function begin() : Iface;

	/**
	 * Commits the changes done inside of the transaction to the storage.
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function commit() : Iface;

	/**
	 * Discards the changes done inside of the transaction.
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Connection instance for method chaining
	 */
	public function rollback() : Iface;

	/**
	 * Deletes the records from the given table
	 *
	 * @param string $table Name of the table
	 * @param array $conditions Key/value pairs of column names and value to compare with
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function delete( string $table, array $conditions = [] ) : \Aimeos\Base\DB\Result\Iface;

	/**
	 * Inserts a record into the given table
	 *
	 * @param string $table Name of the table
	 * @param array $data Key/value pairs of column name/value to insert
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function insert( string $table, array $data ) : \Aimeos\Base\DB\Result\Iface;

	/**
	 * Executes a custom SQL query
	 *
	 * @param string $sql Custom SQL statement
	 * @param array $params List of positional parameters
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function query( string $sql, array $params = [] ) : \Aimeos\Base\DB\Result\Iface;

	/**
	 * Updates the records from the given table
	 *
	 * @param string $table Name of the table
	 * @param array $data Key/value pairs of column name/value to update
	 * @param array $conditions Key/value pairs of column names and value to compare with
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function update( string $table, array $data, array $conditions = [] ) : \Aimeos\Base\DB\Result\Iface;
}
