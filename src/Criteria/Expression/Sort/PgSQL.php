<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2024
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria\Expression\Sort;


/**
 * PostgreSQL implementation for sorting entries
 *
 * @package Base
 * @subpackage Common
 */
class PgSQL extends SQL
{
	/**
	 * Escapes the value so it can be inserted into a SQL statement
	 *
	 * @param string $operator Operator used for the expression
	 * @param string $type Type constant
	 * @param mixed $value Value that the variable or column should be compared to
	 * @return double|string|integer Escaped value
	 */
	protected function escape( string $operator, string $type, $value )
	{
		$value = $this->translateValue( $this->getName(), $value, $type );

		switch( $type )
		{
			case 'bool':
			case 'boolean':
			case \Aimeos\Base\DB\Statement\Base::PARAM_BOOL:
				return ( $value ? "'t'" : "'f'" ); break;
		}

		return parent::escape( $operator, $type, $value );
	}
}
