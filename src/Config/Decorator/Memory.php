<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Config
 */


namespace Aimeos\Base\Config\Decorator;


/**
 * Memory caching decorator for config classes.
 *
 * @package Base
 * @subpackage Config
 */
class Memory
	extends \Aimeos\Base\Config\Decorator\Base
	implements \Aimeos\Base\Config\Decorator\Iface
{
	private array $negCache = [];
	private array $cache = [];
	private array $config;


	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Config\Iface $object Config object or decorator
	 * @param array $config Pre-cached non-shared configuration
	 */
	public function __construct( \Aimeos\Base\Config\Iface $object, array $config = [] )
	{
		parent::__construct( $object );

		$this->config = $config;
	}


	/**
	 * Returns the value of the requested config key.
	 *
	 * @param string $name Path to the requested value like tree/node/classname
	 * @param mixed $default Value returned if requested key isn't found
	 * @return mixed Value associated to the requested key
	 */
	public function get( string $name, $default = null )
	{
		$name = trim( $name, '/' );

		if( isset( $this->negCache[$name] ) ) {
			return $default;
		}

		if( array_key_exists( $name, $this->cache ) ) {
			return $this->cache[$name];
		}

		if( ( $value = $this->getValueFromArray( $this->config, explode( '/', $name ) ) ) === null ) {
			$value = parent::get( $name, null );
		}

		if( $value === null )
		{
			$this->negCache[$name] = true;
			return $default;
		}

		$this->cache[$name] = $value;
		return $value;
	}


	/**
	 * Sets the value for the specified key.
	 *
	 * @param string $name Path to the requested value like tree/node/classname
	 * @param string $value Value that should be associated with the given path
	 * @return \Aimeos\Base\Config\Iface Config instance for method chaining
	 */
	public function set( string $name, $value ) : \Aimeos\Base\Config\Iface
	{
		$name = trim( $name, '/' );

		if( $value !== null )
		{
			$this->cache[$name] = $value;
			unset( $this->negCache[$name] );
		}
		else
		{
			$this->negCache[$name] = true;
		}

		// don't store local configuration
		return $this;
	}


	/**
	 * Returns the requested configuration value from the given array
	 *
	 * @param array $config The array to search in
	 * @param array $parts Configuration path parts to look for inside the array
	 * @return mixed Found configuration value or null if not available
	 */
	protected function getValueFromArray( array $config, array $parts )
	{
		if( ( $key = array_shift( $parts ) ) !== null && isset( $config[$key] ) )
		{
			if( count( $parts ) > 0 ) {
				return $this->getValueFromArray( $config[$key], $parts );
			}

			return $config[$key];
		}

		return null;
	}
}
