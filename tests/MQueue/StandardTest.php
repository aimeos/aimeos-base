<?php

namespace Aimeos\Base\MQueue;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$config = array( 'db' => \TestHelper::getConfig()->get( 'resource/db' ) );
		$this->object = new \Aimeos\Base\MQueue\Standard( $config );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testGetQueue()
	{
		$queue = $this->object->getQueue( 'email' );

		$this->assertInstanceOf( \Aimeos\Base\MQueue\Queue\Iface::class, $queue );
		$this->assertSame( $queue, $this->object->getQueue( 'email' ) );
	}
}
