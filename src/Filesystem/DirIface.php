<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Filesystem
 */


namespace Aimeos\Base\Filesystem;


/**
 * Interface for supporting directories
 *
 * @package Base
 * @subpackage Filesystem
 */
interface DirIface
{
	/**
	 * Tests if the given path is a directory
	 *
	 * @param string $path Path to the file or directory
	 * @return bool True if directory, false if not
	 * @throws \Aimeos\Base\Filesystem\Exception If an error occurs
	 */
	public function isdir( string $path ) : bool;

	/**
	 * Creates a new directory at the given path
	 *
	 * @param string $path Path to the directory
	 * @return \Aimeos\Base\Filesystem\DirIface Filesystem object for fluent interface
	 * @throws \Aimeos\Base\Filesystem\Exception If an error occurs
	 */
	public function mkdir( string $path ) : DirIface;

	/**
	 * Deletes the directory at the given path
	 *
	 * @param string $path Path to the directory
	 * @return \Aimeos\Base\Filesystem\DirIface Filesystem object for fluent interface
	 * @throws \Aimeos\Base\Filesystem\Exception If an error occurs
	 */
	public function rmdir( string $path ) : DirIface;
}
