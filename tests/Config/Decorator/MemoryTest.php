<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2025
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
		$this->assertArrayHasKey( 'database', $result );
		$this->assertArrayHasKey( 'host', $result );
		$this->assertEquals( 'test', $result['database'] );
		$this->assertEquals( '127.0.0.1', $result['host'] );
	}


	public function testGetMergeNested()
	{
		$cfg = ['resource' => ['db' => ['host' => 'localhost', 'database' => 'aimeos']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$local = ['resource' => ['fs' => ['baseurl' => 'https://example.com/uploads']]];
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$this->assertEquals( 'https://example.com/uploads', $object->get( 'resource/fs/baseurl' ) );
		$this->assertEquals( ['host' => 'localhost', 'database' => 'aimeos'], $object->get( 'resource/db' ) );

		$result = $object->get( 'resource' );
		$this->assertArrayHasKey( 'db', $result );
		$this->assertArrayHasKey( 'fs', $result );
		$this->assertEquals( 'aimeos', $result['db']['database'] );
		$this->assertEquals( 'https://example.com/uploads', $result['fs']['baseurl'] );
	}


	public function testGetReplaceList()
	{
		$cfg = ['common' => ['countries' => ['AD', 'DE', 'RS', 'US'], 'currencies' => ['EUR']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$local = ['common' => ['countries' => ['RS']]];
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$this->assertEquals( ['RS'], $object->get( 'common/countries' ) );
		$this->assertEquals( ['countries' => ['RS'], 'currencies' => ['EUR']], $object->get( 'common' ) );
	}


	public function testGetReplaceListEmpty()
	{
		$cfg = ['resource' => ['db' => ['stmt' => ['SET NAMES utf8mb4'], 'host' => 'localhost']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$local = ['resource' => ['db' => ['stmt' => []]]];
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, $local );

		$this->assertEquals( [], $object->get( 'resource/db/stmt' ) );
		$this->assertEquals( ['stmt' => [], 'host' => 'localhost'], $object->get( 'resource/db' ) );
	}


	public function testGetReplaceStacked()
	{
		$cfg = ['common' => ['countries' => ['AD', 'DE', 'RS']]];
		$conf = new \Aimeos\Base\Config\PHPArray( $cfg );

		$inner = new \Aimeos\Base\Config\Decorator\Memory( $conf, ['common' => ['countries' => ['DE', 'RS']]] );
		$object = new \Aimeos\Base\Config\Decorator\Memory( $inner, ['common' => ['countries' => ['RS']]] );

		$this->assertEquals( ['RS'], $object->get( 'common/countries' ) );
	}


	public function testSet()
	{
		$conf = new \Aimeos\Base\Config\PHPArray( [] );
		$object = new \Aimeos\Base\Config\Decorator\Memory( $conf, [] );

		$this->assertInstanceOf( \Aimeos\Base\Config\Iface::class, $object->set( 'notexisting', null ) );
		$this->assertEquals( null, $object->get( 'notexisting' ) );
	}
}
