<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Number;


/**
 * View helper class for formatting numbers.
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the formatted number.
	 *
	 * @param int|double|string $number Number to format
	 * @param int|null $decimals Number of decimals behind the decimal point or null for default value
	 * @return string Formatted number
	 */
	public function transform( $number, int $decimals = null ) : string;
}
