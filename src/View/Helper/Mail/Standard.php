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
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Mail\Iface
{
	private \Aimeos\Base\Mail\Message\Iface $message;


	/**
	 * Initializes the Mail view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param \Aimeos\Base\Mail\Message\Iface $message E-mail message object
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, \Aimeos\Base\Mail\Message\Iface $message )
	{
		parent::__construct( $view );

		$this->message = $message;
	}


	/**
	 * Returns the e-mail message object.
	 *
	 * @return \Aimeos\Base\Mail\Message\Iface E-mail message object
	 */
	public function transform() : \Aimeos\Base\Mail\Message\Iface
	{
		return $this->message;
	}
}
