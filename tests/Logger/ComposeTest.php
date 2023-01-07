<?php

namespace Aimeos\Base\Logger;


/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */
class ComposeTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$basedir = dirname( __DIR__ );

		$loggers = array(
			new \Aimeos\Base\Logger\File( $basedir . '/tmp/error1.log', \Aimeos\Base\Logger\Iface::ERR ),
			new \Aimeos\Base\Logger\File( $basedir . '/tmp/error2.log', \Aimeos\Base\Logger\Iface::INFO, array( 'test' ) ),
			new \Aimeos\Base\Logger\File( $basedir . '/tmp/error3.log', \Aimeos\Base\Logger\Iface::DEBUG ),
		);

		$this->object = new \Aimeos\Base\Logger\Compose( $loggers );
	}


	protected function tearDown() : void
	{
		$basedir = dirname( __DIR__ );

		if( file_exists( $basedir . '/tmp/error2.log' ) ) {
			unlink( $basedir . '/tmp/error2.log' );
		}

		unlink( $basedir . '/tmp/error3.log' );
	}


	public function testLog()
	{
		$this->object->log( 'warning test', \Aimeos\Base\Logger\Iface::WARN );

		$this->assertNotEquals( '', file_get_contents( dirname( __DIR__ ) . '/tmp/error3.log' ) );
	}


	public function testLogFacility()
	{
		$this->object->log( 'warning test', \Aimeos\Base\Logger\Iface::WARN, 'test' );

		$this->assertNotEquals( '', file_get_contents( dirname( __DIR__ ) . '/tmp/error2.log' ) );
		$this->assertNotEquals( '', file_get_contents( dirname( __DIR__ ) . '/tmp/error3.log' ) );
	}
}
