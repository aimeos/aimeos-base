<?php

namespace Aimeos\Base\Filesystem\Manager;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	public function testGet()
	{
		$config = ['fs-media' => ['adapter' => 'Standard', 'basedir' => __DIR__]];
		$object = new \Aimeos\Base\Filesystem\Manager\Standard( $config );

		$this->assertInstanceof( 'Aimeos\Base\Filesystem\Iface', $object->get( 'fs-media' ) );
	}


	public function testGetFallback()
	{
		$config = ['fs' => ['adapter' => 'Standard', 'basedir' => __DIR__]];
		$object = new \Aimeos\Base\Filesystem\Manager\Standard( $config );

		$this->assertInstanceof( 'Aimeos\Base\Filesystem\Iface', $object->get( 'fs-media' ) );
	}


	public function testGetException()
	{
		$object = new \Aimeos\Base\Filesystem\Manager\Standard( [] );

		$this->expectException( \Aimeos\Base\Filesystem\Exception::class );
		$object->get( 'xx' );
	}


	public function tesGetNoAdapter()
	{
		$object = new \Aimeos\Base\Filesystem\Manager\Standard( ['fs' => ['basedir' => __DIR__]] );

		$this->expectException( \Aimeos\Base\Filesystem\Exception::class );
		$object->get( 'fs' );
	}


	public function testSleep()
	{
		$object = new \Aimeos\Base\Filesystem\Manager\Standard( [] );
		$this->assertEquals( ['config' => [], 'objects' => []], $object->__sleep() );
	}
}
