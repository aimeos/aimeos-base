<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Date;


/**
 * View helper class for formatting dates.
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the formatted date.
	 *
	 * @param string $date ISO date and time
	 * @return string Formatted date
	 */
	public function transform( string $date ) : string;
}
