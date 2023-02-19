<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Result;


/**
 * Database result set object for PDO connections.
 *
 * @package Base
 * @subpackage DB
 */
class PDO extends \Aimeos\Base\DB\Result\Base implements \Aimeos\Base\DB\Result\Iface
{
	private \PDOStatement $statement;


	/**
	 * Initializes the result object.
	 *
	 * @param \PDOStatement $stmt Statement object created by PDO
	 */
	public function __construct( \PDOStatement $stmt )
	{
		$this->statement = $stmt;
	}


	/**
	 * Clears the result set if anything is left.
	 */
	public function __destruct()
	{
		$this->statement->closeCursor();
	}


	/**
	 * Returns the number of rows affected by a INSERT, UPDATE or DELETE statement.
	 *
	 * @return int Number of touched records
	 * @throws \Aimeos\Base\DB\Exception if an error occured in the unterlying driver
	 */
	public function affectedRows() : int
	{
		try {
			return $this->statement->rowCount();
		} catch( \PDOException $e ) {
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode(), $e->errorInfo );
		}
	}


	/**
	 * Retrieves the next row from database result set.
	 *
	 * @param int $style The data can be returned as associative or numerical array
	 * @return array|null Numeric or associative array of columns returned by the database or null if no more rows are available
	 * @throws \Aimeos\Base\DB\Exception if an error occured in the unterlying driver or the fetch style is unknown
	 */
	public function fetch( int $style = \Aimeos\Base\DB\Result\Base::FETCH_ASSOC ) : ?array
	{
		try {
			return $this->statement->fetch( $style ? \PDO::FETCH_ASSOC : \PDO::FETCH_NUM ) ?: null;
		} catch( \PDOException $e ) {
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode(), $e->errorInfo );
		}
	}


	/**
	 * Cleans up pending database result sets.
	 *
	 * @return \Aimeos\Base\DB\Result\Iface Result instance for method chaining
	 * @throws \Aimeos\Base\DB\Exception if an error occured in the unterlying driver
	 */
	public function finish() : Iface
	{
		try {
			$this->statement->closeCursor();
		} catch( \PDOException $e ) {
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode(), $e->errorInfo );
		}

		return $this;
	}


	/**
	 * Retrieves the next database result set.
	 *
	 * @return bool True if another result is available, false if not
	 */
	public function nextResult() : bool
	{
		try {
			return $this->statement->nextRowset();
		} catch( \PDOException $e ) {
			return false;
		}
	}
}
