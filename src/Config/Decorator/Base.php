<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Config
 */


namespace Aimeos\Base\Config\Decorator;


/**
 * Base class for all config decorators.
 *
 * @package Base
 * @subpackage Config
 */
abstract class Base implements \Aimeos\Base\Config\Decorator\Iface
{
	private \Aimeos\Base\Config\Iface $object;


	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Config\Iface $object Config object or decorator
	 */
	public function __construct( \Aimeos\Base\Config\Iface $object )
	{
		$this->object = $object;
	}


	/**
	 * Clones the objects inside.
	 */
	public function __clone()
	{
		$this->object = clone $this->object;
	}


	/**
	 * Adds the given configuration and overwrite already existing keys.
	 *
	 * @param array $config Associative list of (multi-dimensional) configuration settings
	 * @return \Aimeos\Base\Config\Iface Config instance for method chaining
	 */
	public function apply( array $config ) : \Aimeos\Base\Config\Iface
	{
		$this->object->apply( $config );
		return $this;
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
		return $this->object->get( $path, $default );
	}


	/**
	 * Sets the value for the specified key.
	 *
	 * @param string $path Path to the requested value like tree/node/classname
	 * @param mixed $value Value that should be associated with the given path
	 */
	public function set( string $path, $value ) : \Aimeos\Base\Config\Iface
	{
		$this->object->set( $path, $value );
		return $this;
	}


	/**
	 * Returns the wrapped config object.
	 *
	 * @return \Aimeos\Base\Config\Iface Config object
	 */
	protected function object() : \Aimeos\Base\Config\Iface
	{
		return $this->object;
	}
}
