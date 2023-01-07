<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Result;


/**
 * Required functions for database result objects.
 *
 * @package Base
 * @subpackage DB
 */
interface Iface
{
	/**
	 * Returns the number of rows affected by a INSERT, UPDATE or DELETE statement.
	 *
	 * @return int Number of touched records
	 */
	public function affectedRows() : int;


	/**
	 * Retrieves all row from database result set.
	 *
	 * @param int $style The data can be returned as associative or numerical array
	 * @return array Numeric or associative array of columns returned by the database
	 */
	public function all( int $style = \Aimeos\Base\DB\Result\Base::FETCH_ASSOC ) : array;


	/**
	 * Retrieves the next row from database result set.
	 *
	 * @param int $style The data can be returned as associative or numerical array
	 * @return array|null Numeric or associative array of columns returned by the database or null if no more rows are available
	 */
	public function fetch( int $style = \Aimeos\Base\DB\Result\Base::FETCH_ASSOC ) : ?array;


	/**
	 * Cleans up pending database result sets.
	 *
	 * @return \Aimeos\Base\DB\Result\Iface Connection instance for method chaining
	 */
	public function finish() : Iface;


	/**
	 * Retrieves next database result set.
	 *
	 * @return bool True if another result is available, false if not
	 */
	public function nextResult() : bool;
}
