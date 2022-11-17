<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2022
 */


class TestHelper
{
	private static $config;
	private static $dbm;


	/**
	 * Returns the configuration object
	 *
	 * @return \Aimeos\Base\Config\Iface Configuration object
	 */
	public static function getConfig()
	{
		if( !isset( self::$config ) ) {
			self::$config = self::createConfig();
		}

		return self::$config;
	}


	/**
	 * Returns the database manager object
	 *
	 * @return \Aimeos\Base\DB\Manager\Iface Database manager object
	 */
	public static function getDBManager()
	{
		return \Aimeos\Base\DB\Factory::create( self::getConfig()->get( 'resource', [] ), 'DBAL' );
	}


	/**
	 * Creates a new configuration object
	 *
	 * @return \Aimeos\Base\Config\Iface Configuration object
	 */
	private static function createConfig()
	{
		$path = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'config';
		$file = __DIR__ . DIRECTORY_SEPARATOR . 'confdoc.ser';

		$object = new \Aimeos\Base\Config\PHPArray( [], $path );
		$object = new \Aimeos\Base\Config\Decorator\Documentor( $object, $file );

		return $object;
	}
}
