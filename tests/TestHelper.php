<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
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
	public static function getConfig() : \Aimeos\Base\Config\Iface
	{
		if( !isset( self::$config ) ) {
			self::$config = self::createConfig();
		}

		return self::$config;
	}


	/**
	 * Returns the database connection object
	 *
	 * @return \Aimeos\Base\DB\Connection\Iface Database connection object
	 */
	public static function getConnection() : \Aimeos\Base\DB\Connection\Iface
	{
		if( !isset( self::$dbm ) ) {
			self::$dbm = new \Aimeos\Base\DB\Manager\Standard( self::getConfig()->get( 'resource', [] ), 'DBAL' );
		}

		return self::$dbm->get();
	}


	/**
	 * Creates a new configuration object
	 *
	 * @return \Aimeos\Base\Config\Iface Configuration object
	 */
	private static function createConfig() : \Aimeos\Base\Config\Iface
	{
		$path = dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'config';
		$file = __DIR__ . DIRECTORY_SEPARATOR . 'confdoc.ser';

		$object = new \Aimeos\Base\Config\PHPArray( [], $path );
		$object = new \Aimeos\Base\Config\Decorator\Documentor( $object, $file );

		return $object;
	}
}
