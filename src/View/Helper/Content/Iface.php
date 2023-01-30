<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Content;


/**
 * View helper class for generating media URLs
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the complete encoded content URL.
	 *
	 * @param string|null $url Absolute, relative or data: URL
	 * @param string $fsname File system name the file is stored at
	 * @return string Complete encoded content URL
	 */
	public function transform( ?string $url, $fsname = 'fs-media', bool $version = false ) : string;
}
