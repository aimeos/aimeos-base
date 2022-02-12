<?php

namespace Aimeos\Base\Config\Decorator;


class ProtectTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$this->object = new \Aimeos\Base\Config\Decorator\Protect( $conf, array( 'client', 'admin' ) );
	}


	public function testGet()
	{
		$this->assertEquals( 'value', $this->object->get( 'client/html/test', 'value' ) );
	}


	public function testGetProtected()
	{
		$this->expectException( 'Aimeos\Base\Config\Exception' );
		$this->object->get( 'resource/db' );
	}


	public function testSet()
	{
		$this->object->set( 'client/html/test', 'value' );
		$this->assertEquals( 'value', $this->object->get( 'client/html/test' ) );
	}


	public function testSetProtected()
	{
		$this->expectException( 'Aimeos\Base\Config\Exception' );
		$this->object->set( 'resource/db', [] );
	}
}
