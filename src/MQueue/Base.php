<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage MQueue
 */


namespace Aimeos\Base\MQueue;


/**
 * Base class for all message queue implementations
 *
 * @package Base
 * @subpackage MQueue
 */
abstract class Base
{
	private array $config;


	/**
	 * Initializes the object
	 *
	 * @param array $config Multi-dimensional associative list of configuration settings
	 */
	public function __construct( array $config )
	{
		$this->config = $config;
	}


	/**
	 * Returns the configuration setting for the given key
	 *
	 * @param string $key Configuration key like "host" or "db/host"
	 * @param mixed $default Default value if no setting is found
	 * @return mixed Configuration setting or default value
	 */
	protected function config( string $key, $default = null )
	{
		$config = $this->config;

		foreach( explode( '/', trim( $key, '/' ) ) as $part )
		{
			if( isset( $config[$part] ) ) {
				$config = $config[$part];
			} else {
				return $default;
			}
		}

		return $config;
	}
}
