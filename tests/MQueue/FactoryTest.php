<?php

namespace Aimeos\Base\MQueue;


class FactoryTest extends \PHPUnit\Framework\TestCase
{
	public function testCreate()
	{
		$result = Factory::create( array( 'adapter' => 'None' ) );
		$this->assertInstanceof( \Aimeos\Base\MQueue\Iface::class, $result );
	}


	public function testCreateNoAdapter()
	{
		$this->expectException( \Aimeos\Base\MQueue\Exception::class );
		Factory::create( [] );
	}


	public function testCreateInvalid()
	{
		$this->expectException( \Aimeos\Base\MQueue\Exception::class );
		Factory::create( array( 'adapter' => 'invalid' ) );
	}
}
