<?php

namespace Aimeos\Base\Process\Decorator;


class CheckTest extends \PHPUnit\Framework\TestCase
{
	private $object;
	private $stub;


	protected function setUp() : void
	{
		$this->stub = $this->getMockBuilder( 'Aimeos\Base\Process\Iface' )
			->onlyMethods( ['isAvailable', 'start', 'wait'] )
			->getMock();

		$this->object = new \Aimeos\Base\Process\Decorator\Check( $this->stub );
	}


	protected function tearDown() : void
	{
		unset( $this->object, $this->stub );
	}


	public function testIsAvailable()
	{
		$this->stub->expects( $this->once() )->method( 'isAvailable' )
			->will( $this->returnValue( true ) );

		$this->assertTrue( $this->object->isAvailable() );
	}


	public function testIsAvailableFalse()
	{
		$this->stub->expects( $this->once() )->method( 'isAvailable' )
			->will( $this->returnValue( false ) );

		$this->assertFalse( $this->object->isAvailable() );
	}


	public function testStart()
	{
		$fcn = function() {};

		$this->stub->expects( $this->once() )->method( 'isAvailable' )
			->will( $this->returnValue( true ) );

		$this->stub->expects( $this->once() )->method( 'start' );

		$this->object->start( $fcn, [] );
	}


	public function testStartNotAvailable()
	{
		$fcn = function() {};

		$this->stub->expects( $this->once() )->method( 'isAvailable' )
			->will( $this->returnValue( false ) );

		$this->stub->expects( $this->never() )->method( 'start' );

		$this->object->start( $fcn, [] );
	}


	public function testWait()
	{
		$this->stub->expects( $this->once() )->method( 'isAvailable' )
			->will( $this->returnValue( true ) );

		$this->stub->expects( $this->once() )->method( 'wait' );

		$this->object->wait();
	}


	public function testWaitNotAvailable()
	{
		$this->stub->expects( $this->once() )->method( 'isAvailable' )
			->will( $this->returnValue( false ) );

		$this->stub->expects( $this->never() )->method( 'wait' );

		$this->object->wait();
	}
}
