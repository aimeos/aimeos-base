<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Number;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->object = new \Aimeos\Base\View\Helper\Number\Standard( $view, '.', ' ', 3 );
	}


	protected function tearDown() : void
	{
		unset( $this->object );
	}


	public function testTransform()
	{
		$this->assertEquals( '1.000', $this->object->transform( 1 ) );
		$this->assertEquals( '1.000', $this->object->transform( 1.0 ) );
		$this->assertEquals( '1 000.000', $this->object->transform( 1000.0 ) );
	}


	public function testTransformNoDecimals()
	{
		$this->assertSame( '1', $this->object->transform( 1, 0 ) );
		$this->assertSame( '1', $this->object->transform( 1.0, 0 ) );
		$this->assertSame( '1 000', $this->object->transform( 1000.0, 0 ) );
	}

}
