<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Connection;


/**
 * Common class for all database connection implementations.
 *
 * @package Base
 * @subpackage DB
 */
abstract class Base implements Iface
{
	private array $params;


	/**
	 * Initializes the base class
	 *
	 * @param array $params Connection parameters
	 */
	public function __construct( array $params = [] )
	{
		$this->params = $params;
	}


	/**
	 * Returns the underlying connection object
	 *
	 * @return mixed Underlying connection object
	 */
	abstract public function getRawObject();


	/**
	 * Escapes the value if necessary for direct inclusion in SQL statement.
	 *
	 * @param string|null $data Value to escape or null for no value
	 * @return string Escaped string
	 */
	public function escape( string $data = null ) : string
	{
		$quoted = $this->getRawObject()->quote( $data );

		if( $quoted[0] === '\'' ) {
			$quoted = substr( $quoted, 1, strlen( $quoted ) - 2 );
		}

		return $quoted;
	}


	/**
	 * Returns the connection parameters
	 *
	 * @return array Parameters to connect to the database server
	 */
	protected function getParameters() : array
	{
		return $this->params;
	}


	/**
	 * Checks if a transaction is currently running
	 *
	 * @return bool True if transaction is currently running, false if not
	 */
	public function inTransaction() : bool
	{
		return true; // safe default
	}


	/**
	 * Returns a quoted identifier for the passed name
	 *
	 * @param string $name Identifier name
	 * @return string Quoted identifier
	 * @throws \Aimeos\Base\DB\Exception If identifier name already contains a quote character
	 */
	public function qi( string $name ) : string
	{
		if( strpos( $name, '"' ) !== false ) {
			throw new \Aimeos\Base\DB\Exception( sprintf( 'Identifier "%1$s"must not contain a quote character' ) );
		}

		return '"' . $name . '"';
	}


	/**
	 * Deletes the records from the given table
	 *
	 * @param string $table Name of the table
	 * @param array $conditions Key/value pairs of column names and value to compare with
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function delete( string $table, array $conditions = [] ) : \Aimeos\Base\DB\Result\Iface
	{
		$sql = 'DELETE FROM ' . $this->qi( $table );

		if( !empty( $conditions ) )
		{
			$where = [];
			$sql .= ' WHERE ';

			foreach( $conditions as $name => $val ) {
				$where[] = $this->qi( $name ) . '=?';
			}

			$sql .= join( ', ', $where );
		}

		$stmt = $this->create( $sql );
		$idx = 0;

		foreach( $conditions as $val ) {
			$stmt->bind( ++$idx, $val, $this->getType( $val ) );
		}

		return $stmt->execute();
	}


	/**
	 * Inserts a record into the given table
	 *
	 * @param string $table Name of the table
	 * @param array $data Key/value pairs of column name/value to insert
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function insert( string $table, array $data ) : \Aimeos\Base\DB\Result\Iface
	{
		if( empty( $data ) ) {
			throw new \Aimeos\Base\DB\Exception( 'Inserting rows requires key/value pairs in second parameter' );
		}

		$cols = [];
		$sql = 'INSERT INTO ' . $this->qi( $table );

		foreach( $data as $name => $val ) {
			$cols[$this->qi( $name )] = '?';
		}

		$sql .= ' (' . join( ', ', array_keys( $cols ) ) . ') VALUES( ' . join( ', ', $cols ) . ')';
		$stmt = $this->create( $sql );
		$idx = 0;

		foreach( $data as $val ) {
			$stmt->bind( ++$idx, $val, $this->getType( $val ) );
		}

		return $stmt->execute();
	}


	/**
	 * Executes a custom SQL query
	 *
	 * @param string $sql Custom SQL statement
	 * @param array $params List of positional parameters
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function query( string $sql, array $params = [] ) : \Aimeos\Base\DB\Result\Iface
	{
		$stmt = $this->create( $sql );
		$idx = 0;

		foreach( $params as $val ) {
			$stmt->bind( ++$idx, $val, $this->getType( $val ) );
		}

		return $stmt->execute();
	}


	/**
	 * Updates the records from the given table
	 *
	 * @param string $table Name of the table
	 * @param array $data Key/value pairs of column name/value to update
	 * @param array $conditions Key/value pairs of column names and value to compare with
	 * @return \Aimeos\Base\DB\Result\Iface Result object
	 */
	public function update( string $table, array $data, array $conditions = [] ) : \Aimeos\Base\DB\Result\Iface
	{
		if( empty( $data ) ) {
			throw new \Aimeos\Base\DB\Exception( 'Updating rows requires key/value pairs in second parameter' );
		}

		$set = $where = [];
		$sql = 'UPDATE ' . $this->qi( $table ) . ' SET ';

		foreach( $data as $name => $val ) {
			$set[] = $this->qi( $name ) . '=?';
		}

		$sql .= join( ', ', $set );

		if( !empty( $conditions ) )
		{
			$sql .= ' WHERE ';

			foreach( $conditions as $name => $val ) {
				$where[] = $this->qi( $name ) . '=?';
			}

			$sql .= join( ', ', $where );
		}

		$stmt = $this->create( $sql );
		$idx = 0;

		foreach( array_merge( $data, $conditions ) as $val ) {
			$stmt->bind( ++$idx, $val, $this->getType( $val ) );
		}

		return $stmt->execute();
	}


	/**
	 * Returns the parameter type for the passed value
	 *
	 * @param mixed $value Parameter value
	 * @return int Parameter type constant
	 */
	protected function getType( $value ) : int
	{
		switch( gettype( $value ) )
		{
			case 'NULL': return \Aimeos\Base\DB\Statement\Base::PARAM_NULL;
			case 'boolean': return \Aimeos\Base\DB\Statement\Base::PARAM_BOOL;
			case 'integer': return \Aimeos\Base\DB\Statement\Base::PARAM_INT;
			case 'double': return \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT;
		}

		return \Aimeos\Base\DB\Statement\Base::PARAM_STR;
	}
}
