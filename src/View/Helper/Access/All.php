<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2025
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Access;


/**
 * View helper class for checking access levels
 *
 * @package Base
 * @subpackage View
 */
class All extends \Aimeos\Base\View\Helper\Base implements Iface
{
	/**
	 * Checks the access level of the current user
	 *
	 * @param string|array $groups Group names that are allowed
	 * @return bool True if access is allowed, false if not
	 */
	public function transform( $groups ) : bool
	{
		return true;
	}
}
