<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2019-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Imageset;


/**
 * View helper class for creating an image srcset string
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the image srcset value for the given image list
	 *
	 * @param array $images List of widths as keys and URLs as values
	 * @return string Image srcset value
	 */
	public function transform( array $images ) : string;
}
