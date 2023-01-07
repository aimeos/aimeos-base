<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Mail;


/**
 * View helper class for creating e-mails.
 *
 * @package Base
 * @subpackage View
 */
interface Iface extends \Aimeos\Base\View\Helper\Iface
{
	/**
	 * Returns the e-mail message object.
	 *
	 * @return \Aimeos\Base\Mail\Message\Iface E-mail message object
	 */
	public function transform() : \Aimeos\Base\Mail\Message\Iface;
}
