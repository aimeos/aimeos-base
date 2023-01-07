<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Filesystem
 */


namespace Aimeos\Base\Filesystem;


/**
 * Interface for supporting metadata
 *
 * @package Base
 * @subpackage Filesystem
 */
interface MetaIface
{
	/**
	 * Returns the file size
	 *
	 * @param string $path Path to the file
	 * @return int Size in bytes
	 * @throws \Aimeos\Base\Filesystem\Exception If an error occurs
	 */
	public function size( string $path ) : int;

	/**
	 * Returns the Unix modification time stamp of the file
	 *
	 * @param string $path Path to the file
	 * @return int Unix time stamp in seconds since 1970-01-01 00:00:00
	 * @throws \Aimeos\Base\Filesystem\Exception If an error occurs
	 */
	public function time( string $path ) : int;
}
