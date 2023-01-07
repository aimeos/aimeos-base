<?php

/**
 * @license LGPLv3, https://opensource.org/licenses/LGPL-3.0
 * @copyright Aimeos (aimeos.org), 2015-2023
 */


namespace Aimeos\Base\View\Helper\Date;


class StandardTest extends \PHPUnit\Framework\TestCase
{
	private $object;


	protected function setUp() : void
	{
		$view = new \Aimeos\Base\View\Standard();
		$this->object = new \Aimeos\Base\View\Helper\Date\Standard( $view, 'd.m.Y' );
	}


	protected function tearDown() : void
	{
		$this->object = null;
	}


	public function testTransform()
	{
		$this->assertEquals( '01.01.2000', $this->object->transform( '2000-01-01 00:00:00' ) );
		$this->assertEquals( '01.01.0000', $this->object->transform( '0000-01-01 00:00:00' ) );
	}

}
