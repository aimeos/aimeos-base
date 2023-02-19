<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria;


/**
 * SQL search class
 *
 * @package Base
 * @subpackage Common
 */
class SQL extends \Aimeos\Base\Criteria\Base
{
	private ?\Aimeos\Base\Criteria\Expression\Iface $conditions = null;
	private \Aimeos\Base\DB\Connection\Iface $conn;
	private array $sortations = [];
	private int $sliceStart = 0;
	private int $sliceSize = 100;


	/**
	 * Initializes the SQL search object
	 *
	 * @param \Aimeos\Base\DB\Connection\Iface $conn Database connection object
	 */
	public function __construct( \Aimeos\Base\DB\Connection\Iface $conn )
	{
		$this->conn = $conn;
	}


	/**
	 * Creates a new combine expression.
	 *
	 * Available composition operators are:
	 * "&&": term1 AND term2
	 * "||": term1 OR term2
	 * "!": NOT term
	 *
	 * @param string $operator One of the known operators
	 * @param \Aimeos\Base\Criteria\Expression\Compare\Iface[] $list List of expression objects
	 * @return \Aimeos\Base\Criteria\Expression\Combine\Iface Combine expression object
	 */
	public function combine( string $operator, array $list ) : \Aimeos\Base\Criteria\Expression\Combine\Iface
	{
		return new \Aimeos\Base\Criteria\Expression\Combine\SQL( $operator, $list );
	}


	/**
	 * Creates a new compare expression.
	 *
	 * Available comparision operators are:
	 * "==": item EQUAL value
	 * "!=": item NOT EQUAL value
	 * "~=": item LIKE value
	 * "=~": item STARTS WITH value
	 * ">=": item GREATER OR EQUAL value
	 * "<=": item SMALLER OR EQUAL value
	 * ">": item GREATER value
	 * "<": item SMALLER value
	 *
	 * @param string $operator One of the known operators
	 * @param string $name Name of the variable or column that should be used for comparison
	 * @param mixed $value Value the variable or column should be compared to
	 * @return \Aimeos\Base\Criteria\Expression\Compare\Iface Compare expression object
	 */
	public function compare( string $operator, string $name, $value ) : \Aimeos\Base\Criteria\Expression\Compare\Iface
	{
		return new \Aimeos\Base\Criteria\Expression\Compare\SQL( $this->conn, $operator, $name, $value );
	}


	/**
	 * Creates a new sort expression.
	 *
	 * Available sorting operators are:
	 * "+": sort ascending
	 * "-": sort descending
	 *
	 * @param string $operator One of the known operators
	 * @param string $name Name of the variable or column that should be used for sorting
	 * @return \Aimeos\Base\Criteria\Expression\Sort\Iface Sort expression object
	 */
	public function sort( string $operator, string $name ) : \Aimeos\Base\Criteria\Expression\Sort\Iface
	{
		return new \Aimeos\Base\Criteria\Expression\Sort\SQL( $this->conn, $operator, $name );
	}


	/**
	 * Returns the available compare, combine and sort operators.
	 *
	 * @return array Associative list of lists (compare, combine, sort) containing the available operators
	 */
	public function getOperators() : array
	{
		return array(
			'combine' => \Aimeos\Base\Criteria\Expression\Combine\SQL::getOperators(),
			'compare' => \Aimeos\Base\Criteria\Expression\Compare\SQL::getOperators(),
			'sort' => \Aimeos\Base\Criteria\Expression\Sort\SQL::getOperators(),
		);
	}


	/**
	 * Returns the expression string.
	 *
	 * @param array $types Associative list of item names and their types
	 * @param array $translations Associative list of item names that should be translated
	 * @param \Aimeos\Base\Criteria\Plugin\Iface[] $plugins Associative list of item names as keys and plugin objects as values
	 * @param array $funcs Associative list of item names and functions modifying the conditions
	 * @return mixed Data for searching
	 */
	public function getConditionSource( array $types, array $translations = [], array $plugins = [], array $funcs = [] )
	{
		$types['1'] = \Aimeos\Base\DB\Statement\Base::PARAM_INT;

		if( $this->conditions && ( $string = $this->conditions->toSource( $types, $translations, $plugins, $funcs ) ) !== '' ) {
			return $string;
		}

		return '1 = 1';
	}


	/**
	 * Returns the original condition expression objects.
	 *
	 * @return \Aimeos\Base\Criteria\Expression\Iface|null Original expression objects
	 */
	public function getConditions() : ?\Aimeos\Base\Criteria\Expression\Iface
	{
		return $this->conditions;
	}


	/**
	 * Sets the expression objects.
	 *
	 * @param \Aimeos\Base\Criteria\Expression\Iface $conditions Expression object
	 * @return \Aimeos\Base\Criteria\Iface Object instance for fluent interface
	 */
	public function setConditions( \Aimeos\Base\Criteria\Expression\Iface $conditions ) : Iface
	{
		if( $conditions instanceof \Aimeos\Base\Criteria\Expression\Sort\Iface ) {
			throw new \Aimeos\Base\Exception( 'Sortation objects are not allowed' );
		}

		$this->conditions = $conditions;
		return $this;
	}


	/**
	 * Returns the string for sorting the result
	 *
	 * @param array $types Associative list of variable or column names as keys and their corresponding types
	 * @param array $translations Associative list of item names that should be translated
	 * @param array $funcs Associative list of item names and functions modifying the conditions
	 * @return mixed Data for sorting the items
	 */
	public function getSortationSource( array $types, array $translations = [], array $funcs = [] )
	{
		if( empty( $this->sortations ) )
		{
			reset( $types );

			if( ( $name = key( $types ) ) === false ) {
				throw new \Aimeos\Base\Exception( 'No sortation types available' );
			}

			return $this->sort( '+', $name )->toSource( $types, $translations, [], $funcs );
		}


		$sortation = [];

		foreach( $this->sortations as $sortitem )
		{
			if( ( $string = $sortitem->toSource( $types, $translations, [], $funcs ) ) !== '' ) {
				$sortation[] = $string;
			}
		}

		return implode( ', ', $sortation );
	}


	/**
	 * Returns the original sorting array for ordering the results.
	 *
	 * @return array Original sortation list (array of objects)
	 */
	public function getSortations() : array
	{
		return $this->sortations;
	}


	/**
	 * Stores the sortation objects for sorting the result.
	 *
	 * @param \Aimeos\Base\Criteria\Expression\Sort\SQL[] $sortations List of objects implementing \Aimeos\Base\Criteria\Expression\Sort\Iface
	 * @return \Aimeos\Base\Criteria\Iface Object instance for fluent interface
	 */
	public function setSortations( array $sortations ) : Iface
	{
		self::implements( \Aimeos\Base\Criteria\Expression\Sort\Iface::class, $sortations );

		$this->sortations = $sortations;
		return $this;
	}


	/**
	 * Returns the number of requested items.
	 *
	 * @return int Number of items
	 */
	public function getLimit() : int
	{
		return $this->sliceSize;
	}


	/**
	 * Returns the start number of requested items.
	 *
	 * @return int Start number of the items
	 */
	public function getOffset() : int
	{
		return $this->sliceStart;
	}


	/**
	 * Sets the start number and the size of the requested data slice.
	 *
	 * @param int $start Start number of the items
	 * @param int $size Number of items
	 * @return \Aimeos\Base\Criteria\PHP Object instance for fluent interface
	 */
	public function slice( int $offset, int $limit = 100 ) : \Aimeos\Base\Criteria\Iface
	{
		$this->sliceStart = $offset;
		$this->sliceSize = $limit;

		return $this;
	}


	/**
	 * Returns the connection object.
	 *
	 * return \Aimeos\Base\DB\Connection\Iface Connection object
	 */
	public function getConnection() : \Aimeos\Base\DB\Connection\Iface
	{
		return $this->conn;
	}
}
