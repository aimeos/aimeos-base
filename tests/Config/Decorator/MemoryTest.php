<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2022
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
	}


	public function testApply()
	{
		$cfg = ['resource' => ['db' => ['database' => 'test']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$local = ['resource' => ['db' => ['host' => '127.0.0.1']]];
		$this->object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );
		$this->object->apply( ['resource' => ['db' => ['host' => '127.0.0.2', 'database' => 'testdb']]] );

		$result = $this->object->get( 'resource/db', [] );
		$this->assertEquals( 'testdb', $result['database'] );
		$this->assertEquals( '127.0.0.2', $result['host'] );
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
		$this->object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$this->assertEquals( '127.0.0.1', $this->object->get( 'resource/db/host', '127.0.0.2' ) );
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
		$this->object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$result = $this->object->get( 'resource/db', [] );
		$this->assertArrayNotHasKey( 'database', $result );
		$this->assertArrayHasKey( 'host', $result );
	}
}
