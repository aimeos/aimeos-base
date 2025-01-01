<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2025
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Value;


/**
 * View helper class for retrieving a value from an associative array
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Value\Iface
{
	/**
	 * Returns the value for the given key in the array
	 *
	 * @param array|string $values Multi-dimensional associative list of key/value pairs or name of the view variable
	 * @param string $key Parameter key like "name" or "list/test" for associative arrays
	 * @param mixed $default Returned value if no one for key is available
	 * @return mixed Value from the array or default value if not present in array
	 */
	public function transform( $values, $key, $default = null )
	{
		$values = (array) ( is_string( $values ) ? $this->view()->get( $values, [] ) : $values );

		foreach( explode( '/', trim( $key, '/' ) ) as $part )
		{
			if( isset( $values[$part] ) ) {
				$values = $values[$part];
			} else {
				return $default;
			}
		}

		return $values;
	}
}
