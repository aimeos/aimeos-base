<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Mail
 */


namespace Aimeos\Base\Mail;


/**
 * Common interface for creating and sending e-mails.
 *
 * @package Base
 * @subpackage Mail
 */
interface Iface
{
	/**
	 * Creates a new e-mail message object.
	 *
	 * @param string $charset Default charset of the message
	 * @return \Aimeos\Base\Mail\Message\Iface E-mail message object
	 */
	public function create( string $charset = 'UTF-8' ) : \Aimeos\Base\Mail\Message\Iface;

	/**
	 * Sends the e-mail message to the mail server.
	 *
	 * @param \Aimeos\Base\Mail\Message\Iface $message E-mail message object
	 * @return \Aimeos\Base\Mail\Iface Mail instance for method chaining
	 */
	public function send( \Aimeos\Base\Mail\Message\Iface $message ) : Iface;
}
