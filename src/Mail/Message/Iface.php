<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Mail
 */


namespace Aimeos\Base\Mail\Message;


/**
 * Common interface for creating and sending e-mails.
 *
 * @package Base
 * @subpackage Mail
 */
interface Iface
{
	/**
	 * Adds an attachment to the message.
	 *
	 * @param string|null $data Binary or string @author nose
	 * @param string|null $mimetype Mime type of the attachment (e.g. "text/plain", "application/octet-stream", etc.)
	 * @param string|null $filename Name of the attached file (or null if inline disposition is used)
	 * @param string $disposition Type of the disposition ("attachment" or "inline")
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function attach( ?string $data, string $filename = null, string $mimetype = null, string $disposition = 'attachment' ) : Iface;

	/**
	 * Adds a destination e-mail address for a hidden copy of the message.
	 *
	 * @param array|string $email Destination address for a hidden copy
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function bcc( $email ) : Iface;

	/**
	 * Adds a destination e-mail address for a copy of the message.
	 *
	 * @param string $email Destination address for a copy
	 * @param string|null $name Name of the user owning the target mailbox or null for no name
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function cc( string $email, string $name = null ) : Iface;

	/**
	 * Embeds an attachment into the message and returns its reference.
	 *
	 * @param string|null $data Binary or string
	 * @param string|null $mimetype Mime type of the attachment (e.g. "text/plain", "application/octet-stream", etc.)
	 * @param string|null $filename Name of the attached file
	 * @return string Content ID for referencing the attachment in the HTML body
	 */
	public function embed( ?string $data, string $filename = null, string $mimetype = null ) : string;

	/**
	 * Adds a source e-mail address of the message.
	 *
	 * @param string $email Source e-mail address
	 * @param string|null $name Name of the user sending the e-mail or null for no name
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function from( string $email, string $name = null ) : Iface;

	/**
	 * Adds a custom header to the message.
	 *
	 * @param string $name Name of the custom e-mail header
	 * @param string $value Text content of the custom e-mail header
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function header( string $name, string $value ) : Iface;

	/**
	 * Sets the HTML body of the message.
	 *
	 * @param string $message HTML body of the message
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function html( string $message ) : Iface;

	/**
	 * Adds the return e-mail address for the message.
	 *
	 * @param string $email E-mail address which should receive all replies
	 * @param string|null $name Name of the user which should receive all replies or null for no name
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function replyTo( string $email, string $name = null ) : Iface;

	/**
	 * Sends the e-mail message to the mail server.
	 *
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function send() : Iface;

	/**
	 * Sets the e-mail address and name of the sender of the message (higher precedence than "From").
	 *
	 * @param string $email Source e-mail address
	 * @param string|null $name Name of the user who sent the message or null for no name
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function sender( string $email, string $name = null ) : Iface;

	/**
	 * Sets the subject of the message.
	 *
	 * @param string $subject Subject of the message
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function subject( string $subject ) : Iface;

	/**
	 * Sets the text body of the message.
	 *
	 * @param string $message Text body of the message
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function text( string $message ) : Iface;

	/**
	 * Adds a destination e-mail address of the target user mailbox.
	 *
	 * @param string $email Destination address of the target mailbox
	 * @param string|null $name Name of the user owning the target mailbox or null for no name
	 * @return \Aimeos\Base\Mail\Message\Iface Message object
	 */
	public function to( string $email, string $name = null ) : Iface;
}
