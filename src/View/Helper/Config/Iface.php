<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Config;


/**
 * View helper class for retrieving configuration values.
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the config value.
	 *
	 * @param string $name Name of the config key
	 * @param mixed $default Default value if config key is not available
	 * @return mixed Config value or associative list of key/value pairs
	 */
	public function transform( string $name, $default = null );
}
