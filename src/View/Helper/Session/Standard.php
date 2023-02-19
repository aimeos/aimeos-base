<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2017-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Session;


/**
 * View helper class for retrieving session values.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Session\Iface
{
	private \Aimeos\Base\Session\Iface $session;


	/**
	 * Initializes the session view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param \Aimeos\Base\Session\Iface $session Session object
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, \Aimeos\Base\Session\Iface $session )
	{
		parent::__construct( $view );

		$this->session = $session;
	}


	/**
	 * Returns the session value.
	 *
	 * @param string $name Name of the session key
	 * @param mixed $default Default value if session key is not available
	 * @return mixed Session value
	 */
	public function transform( string $name, $default = null )
	{
		return $this->session->get( $name, $default );
	}
}
