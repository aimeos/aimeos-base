<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 * @package Base
 * @subpackage Config
 */


namespace Aimeos\Base\Config\Decorator;


/**
 * Documentor decorator for config classes.
 *
 * @package Base
 * @subpackage Config
 */
class Documentor
	extends \Aimeos\Base\Config\Decorator\Base
	implements \Aimeos\Base\Config\Decorator\Iface
{
	private ConfigFile $file;


	/**
	 * Initializes the decorator.
	 *
	 * @param \Aimeos\Base\Config\Iface $object Config object or decorator
	 * @param string $filename File name the collected configuration is written to
	 */
	public function __construct( \Aimeos\Base\Config\Iface $object, string $filename = 'confdoc.ser' )
	{
		parent::__construct( $object );

		// this object is not cloned!
		$this->file = new ConfigFile( $filename );
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
		$value = parent::get( $name, $default );

		$this->file->set( $name, $value, $default );

		return $value;
	}
}


/**
 * File writer for the documentor decorator config classe.
 *
 * @package Base
 * @subpackage Config
 */
class ConfigFile
{
	private array $config = [];
	private $file;


	/**
	 * Initializes the instance.
	 *
	 * @param string $filename
	 * @throws \Aimeos\Base\Config\Exception If file could not be opened or created
	 */
	public function __construct( string $filename )
	{
		if( ( $this->file = fopen( $filename, 'a' ) ) === false ) {
			throw new \Aimeos\Base\Config\Exception( sprintf( 'Unable to open file "%1$s"', $filename ) );
		}
	}


	/**
	 * Cleans up when the object is destroyed.
	 */
	public function __destruct()
	{
		if( is_resource( $this->file ) )
		{
			if( fwrite( $this->file, serialize( $this->config ) ) === false ) {
				echo 'Unable to write collected configuration to file' . PHP_EOL;
			}

			fclose( $this->file );
		}
	}


	/**
	 * Stores the configuration key, the actual and the default value
	 *
	 * @param string $name Configuration key
	 * @param mixed $value Configuration value
	 * @param mixed $default Default value
	 */
	public function set( string $name, $value, $default )
	{
		$this->config[$name]['value'] = $value;
		$this->config[$name]['default'] = $default;
	}
}
