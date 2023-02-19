<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Session
 */


namespace Aimeos\Base\Session;


/**
 * Implementation without using permanent session.
 *
 * @package Base
 * @subpackage Session
 */
class None extends Base implements \Aimeos\Base\Session\Iface
{
	private array $data = [];


	/**
	 * Remove the given key from the session.
	 *
	 * @param string $name Key of the requested value in the session
	 * @return \Aimeos\Base\Session\Iface Session instance for method chaining
	 */
	public function del( string $name ) : Iface
	{
		unset( $this->data[$name] );
		return $this;
	}


	/**
	 * Returns the value of the requested session key.
	 *
	 * @param string $name Key of the requested value in the session
	 * @param string|null $default Value returned if requested key isn't found
	 * @return string Value associated to the requested key
	 */
	public function get( string $name, $default = null )
	{
		if( array_key_exists( $name, $this->data ) !== false ) {
			return $this->data[$name];
		}

		return $default;
	}


	/**
	 * Sets the value for the specified key.
	 *
	 * @param string $name Key to the value which should be stored in the session
	 * @param string $value Value that should be associated with the given key
	 * @return \Aimeos\Base\Session\Iface Session instance for method chaining
	 */
	public function set( string $name, $value ) : Iface
	{
		$this->data[$name] = $value;
		return $this;
	}
}
