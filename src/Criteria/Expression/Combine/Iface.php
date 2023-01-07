<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria\Expression\Combine;


/**
 * Interface for combining objects.
 *
 * @package Base
 * @subpackage Common
 */
interface Iface extends \Aimeos\Base\Criteria\Expression\Iface
{
	/**
	 * Returns the list of expressions that should be combined.
	 *
	 * @return array List of expressions implementing \Aimeos\Base\Criteria\Expression\Iface
	 */
	public function getExpressions() : array;
}
