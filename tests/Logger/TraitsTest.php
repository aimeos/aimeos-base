<?php

/**
 * @license LGPLv3, http://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2021-2023
 */


namespace Aimeos\Base\Logger;


class TraitsTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = $this->getMockBuilder( \Aimeos\Base\Logger\Errorlog::class )
			->onlyMethods( ['log'] )
			->getMock();
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testEmergency()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->emergency( 'emergency' ) );
	}


	public function testAlert()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->alert( 'alert' ) );
	}


	public function testCritical()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->critical( 'critical' ) );
	}


	public function testError()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->error( 'error' ) );
	}


	public function testWarning()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->warning( 'warning' ) );
	}


	public function testNotice()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->notice( 'notice' ) );
	}


	public function testInfo()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->info( 'info' ) );
	}


	public function testDebug()
	{
		$this->object->expects( $this->once() )->method( 'log' )
			->will( $this->returnSelf() );

		$this->assertInstanceOf( \Aimeos\Base\Logger\Iface::class, $this->object->debug( 'debug' ) );
	}
}
