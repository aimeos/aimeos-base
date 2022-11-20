<?php

namespace Aimeos\Base\DB\Manager;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = new \Aimeos\Base\DB\Manager\Standard( \TestHelper::getConfig()->get( 'resource', [] ) );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testGet()
	{
		$conn = $this->object->get()->close();

		$this->assertInstanceOf( \Aimeos\Base\DB\Connection\Iface::class, $conn );
	}


	public function testGetNew()
	{
		$conn = $this->object->get( 'db', true )->close();

		$this->assertInstanceOf( \Aimeos\Base\DB\Connection\Iface::class, $conn );
	}


	public function testGetFallback()
	{
		$conn = $this->object->get( 'db-test' )->close();

		$this->assertInstanceOf( \Aimeos\Base\DB\Connection\Iface::class, $conn );
	}
}
