<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Config
 */


namespace Aimeos\Base\Config\Decorator;


/**
 * APC caching decorator for config classes.
 *
 * @package Base
 * @subpackage Config
 */
class APC
	extends \Aimeos\Base\Config\Decorator\Base
	implements \Aimeos\Base\Config\Decorator\Iface
{
	private bool $enable;
	private string $prefix;


	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Config\Iface $object Config object or decorator
	 * @param string $prefix Prefix for keys to distinguish several instances
	 */
	public function __construct( \Aimeos\Base\Config\Iface $object, string $prefix = '' )
	{
		parent::__construct( $object );

		$this->enable = function_exists( 'apcu_store' );
		$this->prefix = $prefix;
	}


	/**
	 * Returns the value of the requested config key.
	 *
	 * @param string $path Path to the requested value like tree/node/classname
	 * @param mixed $default Value returned if requested key isn't found
	 * @return mixed Value associated to the requested key
	 */
	public function get( string $path, $default = null )
	{
		if( !$this->enable ) {
			return $this->object()->get( $path, $default );
		}

		$path = trim( $path, '/' );

		// negative cache
		$success = false;
		apcu_fetch( '-' . $this->prefix . $path, $success );

		if( $success === true ) {
			return $default;
		}

		// regular cache
		$success = false;
		$value = apcu_fetch( $this->prefix . $path, $success );

		if( $success === true ) {
			return $value;
		}

		// not cached
		if( ( $value = $this->object()->get( $path, null ) ) === null )
		{
			apcu_store( '-' . $this->prefix . $path, null );
			return $default;
		}

		apcu_store( $this->prefix . $path, $value );

		return $value;
	}


	/**
	 * Sets the value for the specified key.
	 *
	 * @param string $path Path to the requested value like tree/node/classname
	 * @param string $value Value that should be associated with the given path
	 * @return \Aimeos\Base\Config\Iface Config instance for method chaining
	 */
	public function set( string $path, $value ) : \Aimeos\Base\Config\Iface
	{
		if( !$this->enable ) {
			return $this->object()->set( $path, $value );
		}

		$path = trim( $path, '/' );

		$this->object()->set( $path, $value );

		apcu_store( $this->prefix . $path, $value );
		return $this;
	}
}
