<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Metaways Infosystems GmbH, 2011
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Common
 */


namespace Aimeos\Base\Criteria\Plugin;


/**
 * Interface for criteria plugin objects.
 *
 * @package Base
 * @subpackage Common
 */
interface Iface
{
	/**
	 * Translates a value to another one.
	 *
	 * @param mixed $value Value to translate
	 * @param mixed $type Expected value type
	 * @return mixed Translated value
	 */
	public function translate( $value, $type = null );

	/**
	 * Reverses the translation of the value.
	 *
	 * @param mixed $value Value to reverse
	 * @param mixed $type Expected value type
	 * @return mixed Reversed translation
	 */
	public function reverse( $value, $type = null );
}
