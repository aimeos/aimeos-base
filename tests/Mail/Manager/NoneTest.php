<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2024
 */

namespace Aimeos\Base\Mail\Manager;


class NoneTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$this->object = new \Aimeos\Base\Mail\Manager\None();
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testGet()
	{
		$this->assertInstanceOf( \Aimeos\Base\Mail\Iface::class, $this->object->get() );
	}
}
