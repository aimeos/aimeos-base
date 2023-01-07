<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\Config\Decorator;


class MemoryTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$this->object = new \Aimeos\Base\Config\Decorator\Memory( $conf );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testApply()
	{
		$cfg = ['resource' => ['db' => ['database' => 'test']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$local = ['resource' => ['db' => ['host' => '127.0.0.1']]];
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );
		$object->apply( ['resource' => ['db' => ['host' => '127.0.0.2', 'database' => 'testdb']]] );

		$this->assertEquals( 'testdb', $object->get( 'resource/db/database' ) );
		$this->assertEquals( '127.0.0.1', $object->get( 'resource/db/host' ) );
	}


	public function testGetSet()
	{
		$this->object->set( 'resource/db/host', '127.0.0.1' );
		$this->assertEquals( '127.0.0.1', $this->object->get( 'resource/db/host', '127.0.0.2' ) );
	}


	public function testGetLocal()
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$local = ['resource' => ['db' => ['host' => '127.0.0.1']]];
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$this->assertEquals( '127.0.0.1', $object->get( 'resource/db/host', '127.0.0.2' ) );
	}


	public function testGetDefault()
	{
		$this->assertEquals( 3306, $this->object->get( 'resource/db/port', 3306 ) );
	}


	public function testGetOverwrite()
	{
		$cfg = ['resource' => ['db' => ['database' => 'test']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$local = ['resource' => ['db' => ['host' => '127.0.0.1']]];
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$result = $object->get( 'resource/db', [] );
		$this->assertArrayNotHasKey( 'database', $result );
		$this->assertArrayHasKey( 'host', $result );
	}


	public function testSet()
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, [] );

		$this->assertInstanceOf( \Aimeos\Base\Config\Iface::class, $object->set( 'notexisting', null ) );
		$this->assertEquals( null, $object->get( 'notexisting' ) );
	}
}
