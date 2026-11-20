<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2020-2026
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria\Expression;


/**
 * Expression trait with basic methods
 *
 * @package Base
 * @subpackage Common
 */
trait Traits
{
	private array $exprPlugins = [];


	/**
	 * Returns the left side of the compare expression.
	 *
	 * @return string Name of variable or column that should be compared
	 */
	abstract public function getName() : string;


	/**
	 * Translates the sort key into the name required by the storage
	 *
	 * @param array $translations Associative list of variable or column names that should be translated
	 * @param array $funcs Associative list of item names and functions modifying the conditions
	 * @return string Translated name (with replaced parameters if the name is an expression function)
	 */
	public function translate( array $translations, array $funcs = [] ) : ?string
	{
		$name = $this->getName();
		return $this->translateName( $name, $translations, $funcs );
	}


	/**
	 * Checks if the given string is an expression function and returns the parameters.
	 * The parameters will be cut off the function name and will be added to
	 * the given parameter array
	 *
	 * @param string $name Function string to check, will be cut to <function>() (without parameter)
	 * @param array $params Array that will contain the list of parameters afterwards
	 * @return bool True if string is an expression function, false if not
	 */
	protected function isFunction( string &$name, array &$params ) : bool
	{
		$len = strlen( $name );
		if( $len === 0 || $name[$len - 1] !== ')' ) {
			return false;
		}

		if( ( $pos = strpos( $name, '(' ) ) === false ) {
			throw new \Aimeos\Base\Exception( 'Missing opening bracket for function syntax' );
		}

		if( ( $paramstr = substr( $name, $pos, $len - $pos ) ) === false ) {
			throw new \Aimeos\Base\Exception( 'Unable to extract function parameter' );
		}

		if( ( $namestr = substr( $name, 0, $pos ) ) === false ) {
			throw new \Aimeos\Base\Exception( 'Unable to extract function name' );
		}

		$params = json_decode( str_replace( ['(', ')'], ['[', ']'], $paramstr ), true );
		$name = $namestr . '()';

		return true;
	}


	/**
	 * Applies the callback to the string or all strings in nested arrays
	 *
	 * @param mixed $source String or multi-dimensional associative array of values
	 * @param \Closure $fcn Function receiving and returning a string
	 * @return mixed String or multi-dimensional array with all strings processed
	 */
	protected function mapStrings( $source, \Closure $fcn )
	{
		if( is_array( $source ) )
		{
			foreach( $source as $key => $value ) {
				$source[$key] = $this->mapStrings( $value, $fcn );
			}

			return $source;
		}

		return is_string( $source ) ? $fcn( $source ) : $source;
	}


	/**
	 * Translates an expression string and replaces the parameter if it's an expression function.
	 *
	 * Functions with a closure get the parameters and a function for quoting values and
	 * return the translated parts built from them. Only scalar values are quoted, nested
	 * values are removed from lists. Otherwise, the positional parameters
	 * ("$1", "$2", etc.) of the translation are replaced by the quoted parameter values
	 * in one pass so inserted values are never scanned again.
	 *
	 * @param string $name Expresion string or function
	 * @param array $translations Associative list of names and their translations (may include parameter if a name is an expression function)
	 * @param array $funcs Associative list of item names and functions returning the translated parts
	 * @return mixed Translated name (with replaced parameters if the name is an expression function)
	 */
	protected function translateName( string &$name, array $translations = [], array $funcs = [] )
	{
		$params = [];

		if( !$this->isFunction( $name, $params ) ) {
			return array_key_exists( $name, $translations ) ? $translations[$name] : $name;
		}

		$params = (array) $params;
		$escape = fn( $value ) => $this->escape( '==', $this->getParamType( $value ), $value );
		$quote = fn( $value ) => is_array( $value )
			? join( ',', array_map( $escape, array_filter( $value, fn( $v ) => is_scalar( $v ) || $v === null ) ) )
			: $escape( $value );

		if( isset( $funcs[$name] ) ) {
			return $this->translateFunction( $funcs[$name]( $params, $quote ) );
		}

		$replace = fn( $match ) => array_key_exists( $match[1] - 1, $params ) ? (string) $quote( $params[$match[1] - 1] ) : $match[0];
		return $this->mapStrings( $translations[$name] ?? $name, fn( $value ) => preg_replace_callback( '/\$(\d+)/', $replace, $value ) );
	}


	/**
	 * Returns the translation from the parts returned by an expression function
	 *
	 * @param mixed $parts Parts returned by the function, e.g. conditions and the target
	 * @return mixed Translation of the expression function
	 */
	protected function translateFunction( $parts )
	{
		return $parts;
	}


	/**
	 * Translates a value to another one by a plugin if available.
	 *
	 * @param string $name Name of variable or column that should be translated
	 * @param mixed $value Original value
	 * @param mixed $type Value type
	 * @return mixed Translated value
	 */
	protected function translateValue( string $name, $value, $type )
	{
		$plugin = $this->getPlugin( $name );
		return $plugin ? $plugin->translate( $value, $type ) : $value;
	}


	/**
	 * Sets the new plugins for translating values.
	 *
	 * @param \Aimeos\Base\Criteria\Plugin\Iface[] $plugins Associative list of names as keys and plugin items as values
	 */
	protected function setPlugins( array $plugins )
	{
		$this->exprPlugins = \Aimeos\Base\Criteria\Base::implements( \Aimeos\Base\Criteria\Plugin\Iface::class, $plugins );
	}


	/**
	 * Returns the plugin for translating values.
	 *
	 * @param string $name Column name
	 * @return \Aimeos\Base\Criteria\Plugin\Iface|null Plugin item or NULL if not available
	 */
	protected function getPlugin( string $name ) : ?\Aimeos\Base\Criteria\Plugin\Iface
	{
		return $this->exprPlugins[$name] ?? null;
	}


	/**
	 * Escapes the value so it can be inserted into a SQL statement
	 *
	 * @param string $operator Operator used for the expression
	 * @param string $type Type constant
	 * @param mixed $value Value that the variable or column should be compared to
	 * @return double|string|int Escaped value
	 */
	protected function escape( string $operator, string $type, $value )
	{
		$value = $this->translateValue( $this->getName(), $value, $type );

		switch( $type )
		{
			case 'null':
			case \Aimeos\Base\DB\Statement\Base::PARAM_NULL:
				$value = 'null'; break;
			case 'bool':
			case 'boolean':
			case \Aimeos\Base\DB\Statement\Base::PARAM_BOOL:
				$value = (int) (bool) $value; break;
			case 'int':
			case 'integer':
			case \Aimeos\Base\DB\Statement\Base::PARAM_INT:
				$value = $value !== '' ? (int) (string) $value : 'null'; break; // objects must be casted to strings first
			case 'float':
			case \Aimeos\Base\DB\Statement\Base::PARAM_FLOAT:
				$value = $value !== '' ? (float) $value : 'null'; break;
			case 'json':
			case 'string':
			case \Aimeos\Base\DB\Statement\Base::PARAM_STR:
				if( $operator === '~=' ) {
					$value = '\'%' . str_replace( ['#', '%', '_', '['], ['##', '#%', '#_', '#['], $this->getConnection()->escape( (string) $value ) ) . '%\''; break;
				}
				if( $operator === '=~' ) {
					$value = '\'' . str_replace( ['#', '%', '_', '['], ['##', '#%', '#_', '#['], $this->getConnection()->escape( (string) $value ) ) . '%\''; break;
				}
			default: // all other operators: escape in default case
				$value = '\'' . $this->getConnection()->escape( (string) $value ) . '\'';
		}

		return $value;
	}


	/**
	 * @param string &$item Reference to parameter value (will be updated if necessary)
	 *
	 * @param mixed $item Parameter value
	 * @return string Internal parameter type
	 * @throws \Aimeos\Base\Exception If an error occurs
	 */
	abstract protected function getParamType( &$item ) : string;
}
