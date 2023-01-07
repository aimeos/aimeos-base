<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Result;


/**
 * Database result set object for DBAL connections.
 *
 * @package Base
 * @subpackage DB
 */
class DBAL extends \Aimeos\Base\DB\Result\Base implements \Aimeos\Base\DB\Result\Iface
{
	private $result;


	/**
	 * Initializes the result object.
	 *
	 * @param \Doctrine\DBAL\Driver\Statement|\Doctrine\DBAL\Driver\Result $result Result object created by DBAL
	 */
	public function __construct( $result )
	{
		$this->result = $result;
	}


	/**
	 * Clears the result set if anything is left.
	 */
	public function __destruct()
	{
		$class = '\Doctrine\DBAL\Driver\Result';

		if( $this->result instanceof $class ) {
			$this->result->free();
		}

		$class = '\Doctrine\DBAL\Driver\Statement';

		if( $this->result instanceof $class ) {
			$this->result->closeCursor();
		}
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
			return $this->result->rowCount();
		} catch( \Doctrine\DBAL\Driver\Exception $e ) {
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode() );
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
		try
		{
			$class = '\Doctrine\DBAL\Driver\Statement';

			if( $this->result instanceof $class )
			{
				$fetch = $style === \Aimeos\Base\DB\Result\Base::FETCH_NUM ? \PDO::FETCH_NUM : \PDO::FETCH_ASSOC;
				return $this->result->fetch( $fetch ) ?: null;
			}

			if( $style === \Aimeos\Base\DB\Result\Base::FETCH_NUM ) {
				return $this->result->fetchNumeric() ?: null;
			} else {
				return $this->result->fetchAssociative() ?: null;
			}
		}
		catch( \Doctrine\DBAL\Driver\Exception $e )
		{
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode() );
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
		try
		{
			$class = '\Doctrine\DBAL\Driver\Result';

			if( $this->result instanceof $class ) {
				$this->result->free();
			}

			$class = '\Doctrine\DBAL\Driver\Statement';

			if( $this->result instanceof $class ) {
				$this->result->closeCursor();
			}
		}
		catch( \Doctrine\DBAL\Driver\Exception $e )
		{
			throw new \Aimeos\Base\DB\Exception( $e->getMessage(), $e->getCode() );
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
		return false;
	}
}
