<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2018-2023
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
			case \Aimeos\Base\DB\Statement\Base::PARAM_NULL:
				$value = 'null'; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_BOOL:
				$value = ( $value ? "'t'" : "'f'" ); break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_INT:
				$value = (int) $value; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT:
				$value = (double) $value; break;
			case \Aimeos\Base\DB\Statement\Base::PARAM_STR:
				if( $operator === '~=' ) {
					$value = '\'%' . str_replace( ['#', '%', '_', '['], ['##', '#%', '#_', '#['], $this->getConnection()->escape( (string) $value ) ) . '%\''; break;
				}
				if( $operator === '=~' ) {
					$value = '\'' . str_replace( ['#', '%', '_', '['], ['##', '#%', '#_', '#['], $this->getConnection()->escape( (string) $value ) ) . '%\''; break;
				}
				// all other operators: escape in default case
			default:
				$value = '\'' . $this->getConnection()->escape( (string) $value ) . '\'';
		}

		return $value;
	}
}
