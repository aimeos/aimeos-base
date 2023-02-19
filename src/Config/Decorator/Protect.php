<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2016-2023
 * @package Base
 * @subpackage Config
 */


namespace Aimeos\Base\Config\Decorator;


/**
 * Protection decorator for config classes.
 *
 * @package Base
 * @subpackage Config
 */
class Protect
	extends \Aimeos\Base\Config\Decorator\Base
	implements \Aimeos\Base\Config\Decorator\Iface
{
	private array $allow = [];
	private array $deny = [];


	/**
	 * Initializes the decorator
	 *
	 * @param \Aimeos\Base\Config\Iface $object Config object or decorator
	 * @param string[] $allow Allowed prefixes for getting and setting values
	 * @param string[] $deny Denied prefixes for getting and setting values
	 */
	public function __construct( \Aimeos\Base\Config\Iface $object, array $allow = [], array $deny = [] )
	{
		parent::__construct( $object );

		foreach( $allow as $prefix ) {
			$this->allow[] = '#^' . str_replace( '*', '[^/]+', $prefix ) . '#';
		}

		foreach( $deny as $prefix ) {
			$this->deny[] = '#^' . str_replace( '*', '[^/]+', $prefix ) . '#';
		}
	}


	/**
	 * Returns the value of the requested config key
	 *
	 * @param string $name Path to the requested value like tree/node/classname
	 * @param mixed $default Value returned if requested key isn't found
	 * @return mixed Value associated to the requested key
	 * @throws \Aimeos\Base\Config\Exception If retrieving configuration isn't allowed
	 */
	public function get( string $name, $default = null )
	{
		foreach( $this->deny as $deny )
		{
			if( preg_match( $deny, $name ) === 1 )
			{
				foreach( $this->allow as $allow )
				{
					if( preg_match( $allow, $name ) === 1 ) {
						return parent::get( $name, $default );
					}
				}

				throw new \Aimeos\Base\Config\Exception( sprintf( 'Not allowed to access "%1$s" configuration', $name ) );
			}
		}

		return parent::get( $name, $default );
	}
}
