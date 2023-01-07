<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage DB
 */


namespace Aimeos\Base\DB\Manager;


/**
 * Required methods for database manager objects.
 *
 * @package Base
 * @subpackage DB
 */
interface Iface
{
	/**
	 * Returns a database connection.
	 *
	 * @param string $name Name of the resource in configuration
	 * @param bool $new Create a new connection instead of returning the existing one
	 * @return \Aimeos\Base\DB\Connection\Iface
	 */
	public function get( string $name = 'db', bool $new = false ) : \Aimeos\Base\DB\Connection\Iface;
}
