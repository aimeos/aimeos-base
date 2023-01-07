<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Url;


/**
 * Base view helper class for building URLs
 *
 * @package Base
 * @subpackage View
 */
abstract class Base extends \Aimeos\Base\View\Helper\Base
{
	/**
	 * Replaces dangerous characters in the parameters
	 *
	 * @param array $params Associative list of key/value pairs
	 * @param string[] $names Replace characters in the parameters of the given names, empty for all
	 * @return array Associative list with encoded values
	 */
	protected function sanitize( array $params, array $names = ['f_name', 'd_name'] ) : array
	{
		foreach( $params as $key => $value )
		{
			if( is_array( $value ) ) {
				$params[$key] = $this->sanitize( $value, $names );
			} elseif( empty( $names ) || in_array( (string) $key, $names ) ) {
				$params[$key] = \Aimeos\Base\Str::slug( $value );
			}
		}

		return $params;
	}
}
