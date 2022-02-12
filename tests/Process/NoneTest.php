<?php

namespace Aimeos\Base\Process;


class NoneTest extends \PHPUnit\Framework\TestCase
{
	public function testIsAvailable()
	{
		$object = new \Aimeos\Base\Process\None();
		$this->assertFalse( $object->isAvailable() );
	}


	public function testStart()
	{
		$object = new \Aimeos\Base\Process\None();

		$this->assertInstanceOf( \Aimeos\Base\Process\Iface::class, $object->start( function() {}, [] ) );
	}


	public function testWait()
	{
		$object = new \Aimeos\Base\Process\None();

		$this->assertInstanceOf( \Aimeos\Base\Process\Iface::class, $object->wait() );
	}
}
