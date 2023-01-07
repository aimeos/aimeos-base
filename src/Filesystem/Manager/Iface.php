<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Filesystem
 */


namespace Aimeos\Base\Filesystem\Manager;


/**
 * Interface for file system managers
 *
 * @package Base
 * @subpackage Filesystem
 */
interface Iface
{
	/**
	 * Returns the file system for the given name
	 *
	 * @param string $name Key for the file system
	 * @return \Aimeos\Base\Filesystem\Iface File system object
	 * @throws \Aimeos\Base\Filesystem\Exception If an error occurs
	 */
	public function get( string $name ) : \Aimeos\Base\Filesystem\Iface;
}
