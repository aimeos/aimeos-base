<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria\Expression\Sort;


/**
 * SQL implementation for sorting objects.
 *
 * @package Base
 * @subpackage Common
 */
class SQL extends Base
{
	private static $operators = array( '+' => 'ASC', '-' => 'DESC' );
	private \Aimeos\Base\DB\Connection\Iface $conn;


	/**
	 * Initializes the object.
	 *
	 * @param \Aimeos\Base\DB\Connection\Iface $conn Database connection object
	 * @param string $operator Sorting operator ("+": ascending, "-": descending)
	 * @param string $name Name of the variable or column to sort
	 */
	public function __construct( \Aimeos\Base\DB\Connection\Iface $conn, string $operator, string $name )
	{
		if( !isset( self::$operators[$operator] ) ) {
			throw new \Aimeos\Base\Exception( sprintf( 'Invalid operator "%1$s"', $operator ) );
		}

		parent::__construct( $operator, $name );
		$this->conn = $conn;
	}


	/**
	 * Returns the available operators for the expression.
	 *
	 * @return array List of available operators
	 */
	public static function getOperators() : array
	{
		return array_keys( self::$operators );
	}


	/**
	 * Generates a string from the expression objects.
	 *
	 * @param array $types Associative list of variable or column names as keys and their corresponding types
	 * @param array $translations Associative list of variable or column names that should be translated
	 * @param \Aimeos\Base\Criteria\Plugin\Iface[] $plugins Associative list of item names as keys and plugin objects as values
	 * @param array $funcs Associative list of item names and functions modifying the conditions
	 * @return mixed Expression that evaluates to a boolean result
	 */
	public function toSource( array $types, array $translations = [], array $plugins = [], array $funcs = [] )
	{
		$this->setPlugins( $plugins );

		$name = $this->getName();
		$transname = $this->translateName( $name, $translations, $funcs );

		if( !$transname ) {
			throw new \Aimeos\Base\Exception( sprintf( 'Invalid sorting "%1$s"', $this->getName() ) );
		}

		if( !isset( $types[$name] ) ) {
			throw new \Aimeos\Base\Exception( sprintf( 'Invalid name "%1$s"', $name ) );
		}

		return $transname . ' ' . self::$operators[$this->getOperator()];
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


	/**
	 * Returns the internal type of the function parameter.
	 *
	 * @param mixed &$item Reference to parameter value (will be updated if necessary)
	 * @return string Internal parameter type
	 * @throws \Aimeos\Base\Exception If an error occurs
	 */
	protected function getParamType( &$item ) : string
	{
		if( is_null( $item ) ) {
			return \Aimeos\Base\DB\Statement\Base::PARAM_NULL;
		} elseif( is_float( $item ) ) {
			return \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT;
		} elseif( is_int( $item ) ) {
			return \Aimeos\Base\DB\Statement\Base::PARAM_INT;
		}

		return \Aimeos\Base\DB\Statement\Base::PARAM_STR;
	}
}
