<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage View
 */


namespace Aimeos\Base\View\Helper\Config;


/**
 * View helper class for retrieving configuration values.
 *
 * @package Base
 * @subpackage View
 */
class Standard
	extends \Aimeos\Base\View\Helper\Base
	implements \Aimeos\Base\View\Helper\Config\Iface
{
	private \Aimeos\Base\Config\Iface $config;


	/**
	 * Initializes the config view helper.
	 *
	 * @param \Aimeos\Base\View\Iface $view View instance with registered view helpers
	 * @param \Aimeos\Base\Config\Iface $config Configuration object
	 */
	public function __construct( \Aimeos\Base\View\Iface $view, \Aimeos\Base\Config\Iface $config )
	{
		parent::__construct( $view );

		$this->config = $config;
	}


	/**
	 * Returns the config value.
	 *
	 * @param string $name Name of the config key
	 * @param mixed $default Default value if config key is not available
	 * @return mixed Config value or associative list of key/value pairs
	 */
	public function transform( string $name, $default = null )
	{
		return $this->config->get( $name, $default );
	}
}
