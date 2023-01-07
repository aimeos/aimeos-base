<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue\Message;


/**
 * Common interface for all message implementations
 *
 * @package Base
 * @subpackage MQueue
 */
interface Iface
{
	/**
	 * Returns the message body
	 *
	 * @return string Message body
	 */
	public function getBody() : string;
}
