<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Logger;


class ErrorlogTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = new \Aimeos\Base\Logger\Errorlog( \Aimeos\Base\Logger\Iface::DEBUG );
	}


	protected function tearDown() : void
	{
		if( file_exists( 'error.log' ) ) {
			unlink( 'error.log' );
		}
	}


	public function testLog()
	{
		ini_set( "error_log", "error.log" );

		$this->object->log( 'error test' );
		$this->object->log( 'warning test', \Aimeos\Base\Logger\Iface::WARN );
		$this->object->log( 'notice test', \Aimeos\Base\Logger\Iface::NOTICE );
		$this->object->log( 'info test', \Aimeos\Base\Logger\Iface::INFO );
		$this->object->log( 'debug test', \Aimeos\Base\Logger\Iface::DEBUG );
		$this->object->log( array( 'scalar', 'test' ) );

		ini_restore( "error_log" );

		$this->assertFileExists( 'error.log', 'Unable to open file "error.log"' );

		foreach( file( 'error.log' ) as $line ) {
			$this->assertMatchesRegularExpression( '/\[[^\]]+\] <message> \[[^\]]+\] \[[^\]]+\] .+test/', $line, $line );
		}
	}


	public function testLogFacility()
	{
		ini_set( "error_log", "error.log" );

		$this->object = new \Aimeos\Base\Logger\Errorlog( \Aimeos\Base\Logger\Iface::DEBUG, array( 'test' ) );
		$this->object->log( 'info test', \Aimeos\Base\Logger\Iface::INFO, 'info' );

		ini_restore( "error_log" );

		$this->assertFileDoesNotExist( 'error.log', 'File "error.log" should not be created' );
	}


	public function testLogLevel()
	{
		$this->expectException( \Aimeos\Base\Logger\Exception::class );
		$this->object->log( 'wrong loglevel test', -1 );
	}
}
