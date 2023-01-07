<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Param;


/**
 * View helper class for retrieving parameter values.
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the parameter value.
	 *
	 * @param string|null $name Name of the parameter key or null for all parameters
	 * @param mixed $default Default value if parameter key is not available
	 * @param bool $escape Escape HTML if single parameter is returned
	 * @return mixed Parameter value or associative list of key/value pairs
	 */
	public function transform( string $name = null, $default = null, bool $escape = true );
}
