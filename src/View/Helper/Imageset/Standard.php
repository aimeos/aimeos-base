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
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Imageset\Iface
{
	/**
	 * Returns the image srcset value for the given image list
	 *
	 * @param array $images List of widths as keys and URLs as values
	 * @param string $fsname File system name the file is stored at
	 * @return string Image srcset value
	 */
	public function transform( array $images, $fsname = 'fs-media' ) : string
	{
		$srcset = [];
		$view = $this->view();

		foreach( $images as $type => $path ) {
			$srcset[] = $view->content( $path, $fsname ) . ' ' . max( 1, $type ) . 'w';
		}

		return join( ', ', $srcset );
	}
}
