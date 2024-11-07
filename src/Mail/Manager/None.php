<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 * @package Base
 * @subpackage Mail
 */


namespace Aimeos\Base\Mail\Manager;


/**
 * Interface for mailer managers
 *
 * @package Base
 * @subpackage Mail
 */
class None implements Iface
{
	/**
	 * Returns the mailer for the given name
	 *
	 * @param string|null $name Key for the mailer
	 * @return \Aimeos\Base\Mail\Iface Mail object
	 * @throws \Aimeos\Base\Mail\Exception If an error occurs
	 */
	public function get( ?string $name = null ) : \Aimeos\Base\Mail\Iface
	{
		return new \Aimeos\Base\Mail\None();
	}
}
